<?php

namespace Thawani;

class Statistics {
    private $start_date;
    private $end_date;

    public function __construct() {
        $this->start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
        $this->end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : date('Y-m-d');
    }

    public function get_total_sales() {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT SUM(meta_value) as total 
            FROM {$wpdb->prefix}postmeta pm
            JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id
            WHERE pm.meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND p.post_date BETWEEN %s AND %s",
            $this->start_date . ' 00:00:00',
            $this->end_date . ' 23:59:59'
        );

        return $wpdb->get_var($query);
    }

    public function get_orders_count() {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT COUNT(*) 
            FROM {$wpdb->prefix}posts 
            WHERE post_type = 'shop_order'
            AND post_status IN ('wc-completed', 'wc-processing')
            AND post_date BETWEEN %s AND %s",
            $this->start_date . ' 00:00:00',
            $this->end_date . ' 23:59:59'
        );

        return $wpdb->get_var($query);
    }

    public function get_daily_sales() {
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT 
                DATE(p.post_date) as date,
                COUNT(*) as orders,
                SUM(pm.meta_value) as total
            FROM {$wpdb->prefix}posts p
            JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND pm.meta_key = '_order_total'
            AND p.post_date BETWEEN %s AND %s
            GROUP BY DATE(p.post_date)
            ORDER BY date ASC",
            $this->start_date . ' 00:00:00',
            $this->end_date . ' 23:59:59'
        );

        return $wpdb->get_results($query);
    }
}
