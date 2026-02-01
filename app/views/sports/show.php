<?php
$title = e($event['event_title']);
ob_start();
?>

<div class="page-header page-header-event <?= getSportBgClass($event['sport_name']) ?>">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php?route=sports.index"><i class="fas fa-home"></i> Events</a>
            <span>/</span>
            <span><?= e($event['event_title']) ?></span>
        </div>
        <div class="event-header-content">
            <div class="event-badge-large">
                <i class="<?= getSportIcon($event['sport_name']) ?>"></i>
            </div>
            <div class="event-header-info">
                <div class="event-tags">
                    <span class="tag tag-sport"><?= e($event['sport_name']) ?></span>
                    <span class="tag tag-status status-<?= e($event['status']) ?>"><?= ucfirst(e($event['status'])) ?></span>
                </div>
                <h1><?= e($event['event_title']) ?></h1>
                <div class="event-header-meta">
                    <span><i class="fas fa-calendar"></i> <?= date('F d, Y', strtotime($event['event_date'])) ?></span>
                    <span><i class="fas fa-clock"></i> <?= date('h:i A', strtotime($event['event_time'])) ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?= e($event['venue']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="event-details-grid">
        <!-- Main Content -->
        <div class="event-main">
            <!-- Stats Cards -->
            <div class="event-stats-row">
                <div class="stat-card-mini">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number"><?= $event['booking_count'] ?></span>
                        <span class="stat-label">Registered</span>
                    </div>
                </div>
                
                <div class="stat-card-mini">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-chair"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number"><?= $availableSeats ?></span>
                        <span class="stat-label">Available</span>
                    </div>
                </div>
                
                <div class="stat-card-mini">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number"><?= count($teams) ?></span>
                        <span class="stat-label">Teams</span>
                    </div>
                </div>
                
                <div class="stat-card-mini">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number"><?= $event['total_capacity'] ?></span>
                        <span class="stat-label">Capacity</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if ($event['description']): ?>
                <div class="content-card">
                    <h3><i class="fas fa-info-circle"></i> About This Event</h3>
                    <p><?= e($event['description']) ?></p>
                </div>
            <?php endif; ?>

            <!-- Participants -->
            <div class="content-card">
                <div class="card-header-flex">
                    <h3><i class="fas fa-users"></i> Registered Participants</h3>
                    <span class="badge badge-primary"><?= count($participants) ?> Total</span>
                </div>
                
                <?php if (count($participants) > 0): ?>
                    <div class="participants-table-wrapper">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Team</th>
                                    <th>Registered On</th>
                                    <?php if ($auth_user): ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participants as $index => $participant): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <div class="participant-name">
                                                <div class="avatar avatar-sm"><?= strtoupper(substr($participant['student_name'], 0, 1)) ?></div>
                                                <?= e($participant['student_name']) ?>
                                            </div>
                                        </td>
                                        <td><?= e($participant['student_email']) ?></td>
                                        <td>
                                            <?php if ($participant['team_name']): ?>
                                                <span class="badge badge-secondary"><?= e($participant['team_name']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Individual</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($participant['created_at'])) ?></td>
                                        <?php if ($auth_user): ?>
                                            <td>
                                                <button class="btn btn-danger btn-xs" onclick="confirmDeleteParticipant(<?= $participant['id'] ?>, '<?= addslashes($participant['student_name']) ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-user-slash"></i>
                        <h4>No Participants Yet</h4>
                        <p>Be the first to register for this event!</p>
                        <a href="index.php?route=participants.register&event_id=<?= $event['id'] ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i> Register Now
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Teams -->
            <?php if (count($teams) > 0): ?>
                <div class="content-card">
                    <h3><i class="fas fa-flag"></i> Registered Teams</h3>
                    <div class="teams-grid">
                        <?php foreach ($teams as $team): ?>
                            <div class="team-badge">
                                <i class="fas fa-users"></i>
                                <?= e($team) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="event-sidebar">
            <!-- Action Card -->
            <div class="sidebar-card action-card">
                <h3>Join This Event</h3>
                
                <div class="capacity-info">
                    <div class="capacity-bar-large">
                        <div class="capacity-fill" style="width: <?= ($event['booking_count'] / max($event['total_capacity'], 1)) * 100 ?>%"></div>
                    </div>
                    <div class="capacity-text">
                        <span><?= $event['booking_count'] ?> / <?= $event['total_capacity'] ?> spots filled</span>
                    </div>
                </div>
                
                <?php if ($availableSeats > 0): ?>
                    <a href="index.php?route=participants.register&event_id=<?= $event['id'] ?>" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-user-plus"></i> Register as Participant
                    </a>
                    
                    <?php if ($auth_user): ?>
                        <button class="btn btn-outline btn-block" onclick="openAttendModal(<?= $event['id'] ?>, '<?= addslashes($event['event_title']) ?>')">
                            <i class="fas fa-ticket-alt"></i> Attend as Spectator
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn btn-secondary btn-block btn-lg" disabled>
                        <i class="fas fa-ban"></i> Event Full
                    </button>
                <?php endif; ?>
            </div>

            <!-- Event Info -->
            <div class="sidebar-card">
                <h3><i class="fas fa-info-circle"></i> Event Details</h3>
                <ul class="info-list">
                    <li>
                        <span class="info-label"><i class="fas fa-running"></i> Sport</span>
                        <span class="info-value"><?= e($event['sport_name']) ?></span>
                    </li>
                    <li>
                        <span class="info-label"><i class="fas fa-calendar"></i> Date</span>
                        <span class="info-value"><?= date('F d, Y', strtotime($event['event_date'])) ?></span>
                    </li>
                    <li>
                        <span class="info-label"><i class="fas fa-clock"></i> Time</span>
                        <span class="info-value"><?= date('h:i A', strtotime($event['event_time'])) ?></span>
                    </li>
                    <li>
                        <span class="info-label"><i class="fas fa-map-marker-alt"></i> Venue</span>
                        <span class="info-value"><?= e($event['venue']) ?></span>
                    </li>
                    <li>
                        <span class="info-label"><i class="fas fa-flag"></i> Max Teams</span>
                        <span class="info-value"><?= $event['team_limit'] ?></span>
                    </li>
                    <?php if (isset($event['creator_name']) && $event['creator_name']): ?>
                        <li>
                            <span class="info-label"><i class="fas fa-user"></i> Organizer</span>
                            <span class="info-value"><?= e($event['creator_name']) ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Admin Actions -->
            <?php if ($auth_user): ?>
                <div class="sidebar-card">
                    <h3><i class="fas fa-cog"></i> Manage Event</h3>
                    <div class="admin-actions">
                        <a href="index.php?route=sports.edit&id=<?= $event['id'] ?>" class="btn btn-outline btn-block">
                            <i class="fas fa-edit"></i> Edit Event
                        </a>
                        <button class="btn btn-danger btn-block" onclick="confirmDelete(<?= $event['id'] ?>, '<?= addslashes($event['event_title']) ?>')">
                            <i class="fas fa-trash"></i> Delete Event
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Share -->
            <div class="sidebar-card">
                <h3><i class="fas fa-share-alt"></i> Share Event</h3>
                <div class="share-buttons">
                    <button class="share-btn share-copy" onclick="copyEventLink()">
                        <i class="fas fa-link"></i>
                    </button>
                    <a href="mailto:?subject=<?= urlencode($event['event_title']) ?>&body=<?= urlencode('Check out this event: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" class="share-btn share-email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, title) {
    document.getElementById('deleteModalMessage').textContent = `Are you sure you want to delete "${title}"? This action cannot be undone.`;
    document.getElementById('deleteForm').action = `index.php?route=sports.delete&id=${id}`;
    document.getElementById('deleteModal').classList.add('active');
}

function confirmDeleteParticipant(id, name) {
    document.getElementById('deleteModalMessage').textContent = `Are you sure you want to remove "${name}" from this event?`;
    document.getElementById('deleteForm').action = `index.php?route=participants.delete&id=${id}`;
    document.getElementById('deleteModal').classList.add('active');
}

function openAttendModal(eventId, eventTitle) {
    document.getElementById('attendEventId').value = eventId;
    document.getElementById('attendEventTitle').textContent = eventTitle;
    document.getElementById('attendModal').classList.add('active');
}

function copyEventLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showToast('Event link copied to clipboard!', 'success');
    });
}
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
