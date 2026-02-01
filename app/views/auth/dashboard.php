<?php
$title = 'Dashboard';
ob_start();
?>

<div class="dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="dashboard-welcome">
                <h1>Welcome, <?= e($auth_user['name']) ?>! 👋</h1>
                <p>Manage your sports events</p>
            </div>
            <div class="dashboard-actions">
                <a href="index.php?route=sports.create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Event
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['total_events'] ?></h3>
                    <p>Total Events</p>
                </div>
            </div>
            
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['upcoming_events'] ?></h3>
                    <p>Upcoming Events</p>
                </div>
            </div>
            
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['total_participants'] ?></h3>
                    <p>Total Participants</p>
                </div>
            </div>
        </div>

        <!-- My Events -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-calendar"></i> My Events
            </h2>
            
            <?php if (count($upcomingEvents) > 0): ?>
                <div class="events-grid">
                    <?php foreach ($upcomingEvents as $event): ?>
                        <div class="event-card">
                            <div class="event-card-header <?= getSportBgClass($event['sport_name']) ?>">
                                <div class="event-sport-badge">
                                    <i class="<?= getSportIcon($event['sport_name']) ?>"></i>
                                    <?= e($event['sport_name']) ?>
                                </div>
                                <span class="status-badge status-<?= $event['status'] ?>">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </div>
                            <div class="event-card-body">
                                <h3 class="event-title"><?= e($event['event_title']) ?></h3>
                                <div class="event-meta">
                                    <div class="event-meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span><?= date('M d, Y', strtotime($event['event_date'])) ?></span>
                                    </div>
                                    <div class="event-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= e($event['venue']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="event-card-footer">
                                <a href="index.php?route=sports.show&id=<?= $event['id'] ?>" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="index.php?route=sports.edit&id=<?= $event['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Events Yet</h4>
                    <p>Create your first sports event</p>
                    <a href="index.php?route=sports.create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Event
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
