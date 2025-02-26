<?php
/**
 * Plugin Name: Sokochan sync
 * Description: A plugin to schedule cron jobs for Sokochan API actions.
 * Version: 1.1.1
 * Author: Montri Udomariyah
 * Author URI: https://github.com/Anasxrt/Sokochan-sync
 */

// Hook to schedule the cron jobs on plugin activation
register_activation_hook(__FILE__, 'sokochan_check_jobs');

add_action('init', 'sokochan_check_jobs');

// Hook the function to check and schedule jobs
function sokochan_check_jobs() {
    if (!wp_next_scheduled('sync_stock_with_sokochan')) {
        wp_schedule_event(time(), 'hourly', 'sync_stock_with_sokochan');
    }
    if (!wp_next_scheduled('create_order_at_sokochan')) {
        wp_schedule_event(time(), 'hourly', 'create_order_at_sokochan');
    }
    if (!wp_next_scheduled('sync_orders_with_sokochan')) {
        wp_schedule_event(time(), 'hourly', 'sync_orders_with_sokochan');
    }
}

// Hook the functions to the scheduled events
add_action('sync_stock_with_sokochan', 'function_to_sync_stock');
add_action('create_order_at_sokochan', 'function_to_create_order');
add_action('sync_orders_with_sokochan', 'function_to_sync_orders');

function function_to_sync_stock() {
    // Create an instance of the Sokochan_Cron class
    $sokochan = new Sokochan_Cron();
    // Call the method to sync stock
    $sokochan->syncStockWithSokochan();
}

function function_to_create_order() {
    // Create an instance of the Sokochan_Cron class
    $sokochan = new Sokochan_Cron();
    // Call the method to create an order
    $sokochan->createOrderAtSokochan();
}

function function_to_sync_orders() {
    // Create an instance of the Sokochan_Cron class
    $sokochan = new Sokochan_Cron();
    // Call the method to sync orders
    $sokochan->syncOrderStatus();
}    

// Hook to clear the scheduled events on plugin deactivation
register_deactivation_hook(__FILE__, 'sokochan_clear_cron_jobs');

function sokochan_clear_cron_jobs() {
    $timestamp = wp_next_scheduled('sync_stock_with_sokochan');
    wp_unschedule_event($timestamp, 'sync_stock_with_sokochan');

    $timestamp = wp_next_scheduled('create_order_at_sokochan');
    wp_unschedule_event($timestamp, 'create_order_at_sokochan');

    $timestamp = wp_next_scheduled('sync_orders_with_sokochan');
    wp_unschedule_event($timestamp, 'sync_orders_with_sokochan');
}
