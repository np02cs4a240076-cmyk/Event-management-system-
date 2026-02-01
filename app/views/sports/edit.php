<?php
$title = 'Edit Event';
$sports = ['Basketball', 'Football', 'Soccer', 'Volleyball', 'Cricket', 'Tennis', 'Baseball', 'Swimming', 'Athletics', 'Badminton'];
$emojis = ['🏀', '🏈', '⚽', '🏐', '🏏', '🎾', '⚾', '🏊', '🏃', '🏸'];
ob_start();
?>

<div class="page-header page-header-sm">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php?route=sports.index"><i class="fas fa-home"></i> Events</a>
            <span>/</span>
            <a href="index.php?route=sports.show&id=<?= $event['id'] ?>"><?= e($event['event_title']) ?></a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1><i class="fas fa-edit"></i> Edit Event</h1>
    </div>
</div>

<div class="container">
    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2>Update Event Details</h2>
                <p>Modify the event information below</p>
            </div>
            
            <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?route=sports.update&id=<?= $event['id'] ?>" method="POST" class="event-form" id="eventForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                
                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sport_name" class="form-label">
                                Sport Name <span class="text-danger">*</span>
                            </label>
                            <select id="sport_name" name="sport_name" class="form-control form-select <?= isset($errors['sport_name']) ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a sport</option>
                                <?php foreach ($sports as $i => $sport): ?>
                                    <option value="<?= e($sport) ?>" <?= $event['sport_name'] == $sport ? 'selected' : '' ?>>
                                        <?= $emojis[$i] ?> <?= e($sport) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['sport_name'])): ?>
                                <div class="invalid-feedback"><?= e($errors['sport_name']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_title" class="form-label">
                                Event Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="event_title" name="event_title" class="form-control <?= isset($errors['event_title']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e($event['event_title']) ?>" required>
                            <?php if (isset($errors['event_title'])): ?>
                                <div class="invalid-feedback"><?= e($errors['event_title']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="venue" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Venue <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="venue" name="venue" class="form-control <?= isset($errors['venue']) ? 'is-invalid' : '' ?>" 
                               value="<?= e($event['venue']) ?>" required>
                        <?php if (isset($errors['venue'])): ?>
                            <div class="invalid-feedback"><?= e($errors['venue']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3><i class="fas fa-clock"></i> Date & Time</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="event_date" class="form-label">
                                Event Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="event_date" name="event_date" class="form-control <?= isset($errors['event_date']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e($event['event_date']) ?>" required>
                            <?php if (isset($errors['event_date'])): ?>
                                <div class="invalid-feedback"><?= e($errors['event_date']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_time" class="form-label">
                                Event Time
                            </label>
                            <input type="time" id="event_time" name="event_time" class="form-control" 
                                   value="<?= e($event['event_time']) ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3><i class="fas fa-users"></i> Capacity Settings</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_limit" class="form-label">
                                Maximum Teams
                            </label>
                            <input type="number" id="team_limit" name="team_limit" class="form-control <?= isset($errors['team_limit']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e($event['team_limit']) ?>" min="1" max="100">
                            <?php if (isset($errors['team_limit'])): ?>
                                <div class="invalid-feedback"><?= e($errors['team_limit']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="total_capacity" class="form-label">
                                Total Capacity
                            </label>
                            <input type="number" id="total_capacity" name="total_capacity" class="form-control <?= isset($errors['total_capacity']) ? 'is-invalid' : '' ?>" 
                                   value="<?= e($event['total_capacity']) ?>" min="1" max="10000">
                            <?php if (isset($errors['total_capacity'])): ?>
                                <div class="invalid-feedback"><?= e($errors['total_capacity']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status" class="form-label">
                            Event Status
                        </label>
                        <select id="status" name="status" class="form-control form-select">
                            <option value="upcoming" <?= $event['status'] == 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                            <option value="ongoing" <?= $event['status'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                            <option value="completed" <?= $event['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $event['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3><i class="fas fa-align-left"></i> Description</h3>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">
                            Event Description
                        </label>
                        <textarea id="description" name="description" class="form-control" rows="4"><?= e($event['description']) ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="index.php?route=sports.show&id=<?= $event['id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $event['id'] ?>, '<?= addslashes($event['event_title']) ?>')">
                        <i class="fas fa-trash"></i> Delete Event
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Event Stats -->
        <div class="stats-sidebar">
            <div class="stat-box">
                <h3>Event Statistics</h3>
                <div class="stat-item">
                    <span class="stat-label">Current Bookings</span>
                    <span class="stat-value"><?= $event['booking_count'] ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Available Seats</span>
                    <span class="stat-value"><?= $event['total_capacity'] - $event['booking_count'] ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Capacity Used</span>
                    <span class="stat-value"><?= round(($event['booking_count'] / max($event['total_capacity'], 1)) * 100) ?>%</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Created</span>
                    <span class="stat-value"><?= date('M d, Y', strtotime($event['created_at'])) ?></span>
                </div>
            </div>
            
            <div class="danger-zone">
                <h4><i class="fas fa-exclamation-triangle"></i> Danger Zone</h4>
                <p>Deleting an event will also remove all participant registrations.</p>
                <button type="button" class="btn btn-danger btn-block" onclick="confirmDelete(<?= $event['id'] ?>, '<?= addslashes($event['event_title']) ?>')">
                    <i class="fas fa-trash"></i> Delete This Event
                </button>
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
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
