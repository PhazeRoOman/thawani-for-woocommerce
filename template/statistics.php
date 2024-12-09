<?php
if (!defined('ABSPATH')) exit;

$statistics = new \Thawani\Statistics();
$total_sales = $statistics->get_total_sales();
$orders_count = $statistics->get_orders_count();
$daily_sales = $statistics->get_daily_sales();
?>

<div class="wrap">
    <h1><?php _e('Thawani إحصائيات المبيعات', 'thawani'); ?></h1>
    
    <div class="thawani-stats-filters">
        <form method="get">
            <input type="hidden" name="page" value="thawani_statistics">
            <label>
                <?php _e('من تاريخ:', 'thawani'); ?>
                <input type="date" name="start_date" value="<?php echo esc_attr($_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'))); ?>">
            </label>
            <label>
                <?php _e('إلى تاريخ:', 'thawani'); ?>
                <input type="date" name="end_date" value="<?php echo esc_attr($_GET['end_date'] ?? date('Y-m-d')); ?>">
            </label>
            <button type="submit" class="button button-primary"><?php _e('تطبيق', 'thawani'); ?></button>
        </form>
    </div>

    <div class="thawani-stats-summary">
        <div class="thawani-stat-box">
            <h3><?php _e('إجمالي المبيعات', 'thawani'); ?></h3>
            <p><?php echo wc_price($total_sales); ?></p>
        </div>
        <div class="thawani-stat-box">
            <h3><?php _e('عدد الطلبات', 'thawani'); ?></h3>
            <p><?php echo esc_html($orders_count); ?></p>
        </div>
    </div>

    <div class="thawani-stats-chart">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<style>
.thawani-stats-summary {
    display: flex;
    gap: 20px;
    margin: 20px 0;
}
.thawani-stat-box {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    flex: 1;
}
.thawani-stats-filters {
    background: #fff;
    padding: 15px;
    margin: 20px 0;
    border-radius: 5px;
}
.thawani-stats-filters form {
    display: flex;
    gap: 15px;
    align-items: center;
}
.thawani-stats-chart {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    margin-top: 20px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const data = <?php echo json_encode($daily_sales); ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.date),
            datasets: [{
                label: '<?php _e('المبيعات اليومية', 'thawani'); ?>',
                data: data.map(item => item.total),
                borderColor: '#6fbf49',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
