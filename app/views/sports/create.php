<?php
$title = 'Create Event';
ob_start();
?>

<div class="page-header page-header-sm">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php?route=sports.index"><i class="fas fa-home"></i> Events</a>
            <span>/</span>
            <span>Create Event</span>
        </div>
        <h1><i class="fas fa-plus-circle"></i> Create New Event</h1>
    </div>
</div>

<div class="container">
    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <h2>Event Details</h2>
                <p>Fill in the information below to create a new sports event</p>
            </div>
            
            <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?route=sports.store" method="POST" class="event-form" id="eventForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                
                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sport_name" class="form-label">
                                Sport Name <span class="text-danger">*</span>
                            </label>
                            <select id="sport_name" name="sport_name" class="form-control form-select <?= isset($errors['sport_name']) ? 'is-invalid' : '' ?>" required>
                                <option value="">Select a sport</option>
                                <option value="Basketball">🏀 Basketball</option>
                                <option value="Football">🏈 Football</option>
                                <option value="Soccer">⚽ Soccer</option>
                                <option value="Volleyball">🏐 Volleyball</option>
                                <option value="Cricket">🏏 Cricket</option>
                                <option value="Tennis">🎾 Tennis</option>
                                <option value="Baseball">⚾ Baseball</option>
                                <option value="Swimming">🏊 Swimming</option>
                                <option value="Athletics">🏃 Athletics</option>
                                <option value="Badminton">🏸 Badminton</option>
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
                                   placeholder="e.g., Annual Basketball Championship" required>
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
                               placeholder="e.g., Main Sports Arena, Building A" required>
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
                                   min="<?= date('Y-m-d') ?>" required>
                            <?php if (isset($errors['event_date'])): ?>
                                <div class="invalid-feedback"><?= e($errors['event_date']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_time" class="form-label">
                                Event Time
                            </label>
                            <input type="time" id="event_time" name="event_time" class="form-control" value="09:00">
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
                                   value="10" min="1" max="100">
                            <small class="form-text">Maximum number of teams allowed</small>
                            <?php if (isset($errors['team_limit'])): ?>
                                <div class="invalid-feedback"><?= e($errors['team_limit']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="total_capacity" class="form-label">
                                Total Capacity
                            </label>
                            <input type="number" id="total_capacity" name="total_capacity" class="form-control <?= isset($errors['total_capacity']) ? 'is-invalid' : '' ?>" 
                                   value="100" min="1" max="10000">
                            <small class="form-text">Maximum number of participants/attendees</small>
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
                            <option value="upcoming" selected>Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3><i class="fas fa-align-left"></i> Description</h3>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">
                            Event Description
                        </label>
                        <textarea id="description" name="description" class="form-control" rows="4" 
                                  placeholder="Provide details about the event, rules, requirements, etc."></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="index.php?route=sports.index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check"></i> Create Event
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Preview Card -->
        <div class="preview-card">
            <h3><i class="fas fa-eye"></i> Preview</h3>
            <div class="event-card preview">
                <div class="event-card-header bg-gradient-primary" id="previewHeader">
                    <div class="event-sport-badge">
                        <i class="fas fa-trophy" id="previewSportIcon"></i>
                        <span id="previewSport">Select Sport</span>
                    </div>
                    <div class="event-status status-upcoming">Upcoming</div>
                </div>
                <div class="event-card-body">
                    <h3 class="event-title" id="previewTitle">Event Title</h3>
                    <div class="event-meta">
                        <div class="event-meta-item">
                            <i class="fas fa-calendar"></i>
                            <span id="previewDate">Select Date</span>
                        </div>
                        <div class="event-meta-item">
                            <i class="fas fa-clock"></i>
                            <span id="previewTime">09:00 AM</span>
                        </div>
                        <div class="event-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span id="previewVenue">Enter Venue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Live preview
document.getElementById('sport_name').addEventListener('change', function() {
    document.getElementById('previewSport').textContent = this.value || 'Select Sport';
});

document.getElementById('event_title').addEventListener('input', function() {
    document.getElementById('previewTitle').textContent = this.value || 'Event Title';
});

document.getElementById('venue').addEventListener('input', function() {
    document.getElementById('previewVenue').textContent = this.value || 'Enter Venue';
});

document.getElementById('event_date').addEventListener('change', function() {
    if (this.value) {
        const date = new Date(this.value);
        document.getElementById('previewDate').textContent = date.toLocaleDateString('en-US', { 
            month: 'long', day: 'numeric', year: 'numeric' 
        });
    }
});

document.getElementById('event_time').addEventListener('change', function() {
    if (this.value) {
        const [hours, minutes] = this.value.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        document.getElementById('previewTime').textContent = `${hour12}:${minutes} ${ampm}`;
    }
});

// Form validation
document.getElementById('eventForm').addEventListener('submit', function(e) {
    const sportName = document.getElementById('sport_name').value;
    const eventTitle = document.getElementById('event_title').value.trim();
    const venue = document.getElementById('venue').value.trim();
    const eventDate = document.getElementById('event_date').value;
    
    if (!sportName || !eventTitle || !venue || !eventDate) {
        e.preventDefault();
        showToast('Please fill in all required fields', 'error');
    }
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
