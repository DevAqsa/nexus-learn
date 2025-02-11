<?php
if (!defined('ABSPATH')) exit;

$user_id = get_current_user_id();
$membership_status = get_user_meta($user_id, 'nl_membership_status', true) ?: 'free';
$membership_expiry = get_user_meta($user_id, 'nl_membership_expiry', true);
$current_plan = get_user_meta($user_id, 'nl_current_plan', true) ?: 'Free';

// Get membership plans
$membership_plans = [
    'free' => [
        'name' => 'Free Plan',
        'price' => '0',
        'features' => [
            'Access to free courses',
            'Basic progress tracking',
            'Limited quiz attempts'
        ]
    ],
    'basic' => [
        'name' => 'Basic Plan',
        'price' => '9.99',
        'features' => [
            'Access to all basic courses',
            'Unlimited quiz attempts',
            'Course certificates',
            'Priority support'
        ]
    ],
    'premium' => [
        'name' => 'Premium Plan',
        'price' => '19.99',
        'features' => [
            'Access to all courses including premium',
            'Exclusive webinars',
            'Downloadable resources',
            '1-on-1 mentoring sessions',
            'Advanced analytics'
        ]
    ]
];
?>

<div class="nl-membership-section nl-content-section">
    <!-- Current Membership Status -->
    <div class="nl-membership-header">
        <h2><?php _e('Membership Status', 'nexuslearn'); ?></h2>
        <div class="nl-current-plan">
            <div class="nl-plan-badge <?php echo esc_attr($membership_status); ?>">
                <?php echo esc_html($current_plan); ?>
            </div>
            <?php if ($membership_expiry): ?>
                <div class="nl-expiry-date">
                    <?php printf(
                        __('Valid until: %s', 'nexuslearn'),
                        date_i18n(get_option('date_format'), strtotime($membership_expiry))
                    ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Membership Plans -->
    <div class="nl-membership-plans">
        <h3><?php _e('Available Plans', 'nexuslearn'); ?></h3>
        <div class="nl-plans-grid">
            <?php foreach ($membership_plans as $plan_id => $plan): ?>
                <div class="nl-plan-card <?php echo $current_plan === $plan['name'] ? 'current' : ''; ?>">
                    <div class="nl-plan-header">
                        <h4><?php echo esc_html($plan['name']); ?></h4>
                        <div class="nl-plan-price">
                            <span class="nl-currency">$</span>
                            <span class="nl-amount"><?php echo esc_html($plan['price']); ?></span>
                            <span class="nl-period">/month</span>
                        </div>
                    </div>
                    
                    <div class="nl-plan-features">
                        <ul>
                            <?php foreach ($plan['features'] as $feature): ?>
                                <li>
                                    <i class="dashicons dashicons-yes-alt"></i>
                                    <?php echo esc_html($feature); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="nl-plan-action">
                        <?php if ($current_plan === $plan['name']): ?>
                            <button class="nl-button nl-button-current" disabled>
                                <?php _e('Current Plan', 'nexuslearn'); ?>
                            </button>
                        <?php else: ?>
                            <button class="nl-button nl-button-primary nl-upgrade-plan" 
                                    data-plan="<?php echo esc_attr($plan_id); ?>">
                                <?php _e('Upgrade Now', 'nexuslearn'); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Membership Benefits -->
    <div class="nl-membership-benefits">
        <h3><?php _e('Membership Benefits', 'nexuslearn'); ?></h3>
        <div class="nl-benefits-grid">
            <div class="nl-benefit-card">
                <i class="dashicons dashicons-welcome-learn-more"></i>
                <h4><?php _e('Access All Courses', 'nexuslearn'); ?></h4>
                <p><?php _e('Get unlimited access to our entire course library', 'nexuslearn'); ?></p>
            </div>
            <div class="nl-benefit-card">
                <i class="dashicons dashicons-groups"></i>
                <h4><?php _e('Community Access', 'nexuslearn'); ?></h4>
                <p><?php _e('Join our exclusive learning community', 'nexuslearn'); ?></p>
            </div>
            <div class="nl-benefit-card">
                <i class="dashicons dashicons-certificates"></i>
                <h4><?php _e('Verified Certificates', 'nexuslearn'); ?></h4>
                <p><?php _e('Earn certificates for completed courses', 'nexuslearn'); ?></p>
            </div>
        </div>
    </div>

    <!-- Membership History -->
    <?php
    $membership_history = get_user_meta($user_id, 'nl_membership_history', true) ?: [];
    if (!empty($membership_history)):
    ?>
    <div class="nl-membership-history">
        <h3><?php _e('Membership History', 'nexuslearn'); ?></h3>
        <table class="nl-history-table">
            <thead>
                <tr>
                    <th><?php _e('Plan', 'nexuslearn'); ?></th>
                    <th><?php _e('Start Date', 'nexuslearn'); ?></th>
                    <th><?php _e('End Date', 'nexuslearn'); ?></th>
                    <th><?php _e('Status', 'nexuslearn'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($membership_history as $history): ?>
                    <tr>
                        <td><?php echo esc_html($history['plan']); ?></td>
                        <td><?php echo date_i18n(get_option('date_format'), strtotime($history['start_date'])); ?></td>
                        <td><?php echo date_i18n(get_option('date_format'), strtotime($history['end_date'])); ?></td>
                        <td>
                            <span class="nl-status-badge <?php echo esc_attr($history['status']); ?>">
                                <?php echo esc_html(ucfirst($history['status'])); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<style>
/* Membership Section Styles */
.nl-membership-section {
    padding: 2rem;
}

.nl-membership-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.nl-plan-badge {
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.nl-plan-badge.free {
    background: #e5e7eb;
    color: #374151;
}

.nl-plan-badge.basic {
    background: #dbeafe;
    color: #1e40af;
}

.nl-plan-badge.premium {
    background: #fef3c7;
    color: #92400e;
}

.nl-plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.nl-plan-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.nl-plan-card:hover {
    transform: translateY(-4px);
}

.nl-plan-card.current {
    border: 2px solid #6366f1;
}

.nl-plan-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.nl-plan-price {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 1rem 0;
}

.nl-currency,
.nl-period {
    font-size: 1rem;
    color: #6b7280;
}

.nl-plan-features ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nl-plan-features li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    color: #4b5563;
}

.nl-plan-features .dashicons {
    color: #6366f1;
}

.nl-plan-action {
    margin-top: 2rem;
    text-align: center;
}

.nl-button {
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: background-color 0.2s;
}

.nl-button-primary {
    background: #6366f1;
    color: white;
}

.nl-button-primary:hover {
    background: #4f46e5;
}

.nl-button-current {
    background: #e5e7eb;
    color: #374151;
    cursor: not-allowed;
}

.nl-benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.nl-benefit-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.nl-benefit-card .dashicons {
    font-size: 2.5rem;
    width: 2.5rem;
    height: 2.5rem;
    color: #6366f1;
    margin-bottom: 1rem;
}

.nl-history-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1.5rem;
}

.nl-history-table th,
.nl-history-table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.nl-history-table th {
    background: #f3f4f6;
    font-weight: 600;
    text-align: left;
}

.nl-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.nl-status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.nl-status-badge.expired {
    background: #fee2e2;
    color: #991b1b;
}

@media (max-width: 768px) {
    .nl-membership-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .nl-plans-grid,
    .nl-benefits-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('.nl-upgrade-plan').on('click', function() {
        const planId = $(this).data('plan');
        // Handle plan upgrade - implement payment gateway integration
        console.log('Upgrading to plan:', planId);
    });
});
</script>