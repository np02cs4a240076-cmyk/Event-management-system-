<?php
$title = 'Register for Event';
ob_start();
?>

<div class="page-header page-header-sm">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php?route=sports.index"><i class="fas fa-home"></i> Events</a>
            <span>/</span>
            <?php if ($event): ?>
                <a href="index.php?route=sports.show&id=<?= $event['id'] ?>"><?= e($event['event_title']) ?></a>
                <span>/</span>
            <?php endif; ?>
            <span>Register</span>
        </div>
        <h1><i class="fas fa-user-plus"></i> Participant Registration</h1>
    </div>
</div>

<div class="container">
    <div class="registration-container">
        <div class="registration-form-card">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <h2>Register as Participant</h2>
                <p>Fill in your details to register for a sports event</p>
            </div>
            
            <form action="index.php?route=participants.store" method="POST" class="registration-form" id="registrationForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                
                <?php if (!$event): ?>
                    <div class="form-group">
                        <label for="sport_event_id" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Select Event <span class="text-danger">*</span>
                        </label>
                        <select id="sport_event_id" name="sport_event_id" class="form-control form-select <?= isset($errors['sport_event_id']) ? 'is-invalid' : '' ?>" required>
                            <option value="">Choose an event to register</option>
                            <?php foreach ($events as $evt): ?>
                                <option value="<?= $evt['id'] ?>" data-sport="<?= e($evt['sport_name']) ?>" data-date="<?= date('M d, Y', strtotime($evt['event_date'])) ?>" data-venue="<?= e($evt['venue']) ?>">
                                    <?= e($evt['event_title']) ?> - <?= e($evt['sport_name']) ?> (<?= date('M d', strtotime($evt['event_date'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['sport_event_id'])): ?>
                            <div class="invalid-feedback"><?= e($errors['sport_event_id']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="selected-event-info" id="selectedEventInfo" style="display: none;">
                        <div class="event-preview-card">
                            <i class="fas fa-trophy"></i>
                            <div class="event-preview-details">
                                <h4 id="selectedEventName">Event Name</h4>
                                <p>
                                    <span id="selectedEventSport">Sport</span> •
                                    <span id="selectedEventDate">Date</span> •
                                    <span id="selectedEventVenue">Venue</span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="sport_event_id" value="<?= $event['id'] ?>">
                    
                    <div class="event-preview-card featured">
                        <div class="event-preview-icon <?= getSportBgClass($event['sport_name']) ?>">
                            <i class="<?= getSportIcon($event['sport_name']) ?>"></i>
                        </div>
                        <div class="event-preview-details">
                            <span class="event-preview-sport"><?= e($event['sport_name']) ?></span>
                            <h4><?= e($event['event_title']) ?></h4>
                            <p>
                                <i class="fas fa-calendar"></i> <?= date('F d, Y', strtotime($event['event_date'])) ?> •
                                <i class="fas fa-map-marker-alt"></i> <?= e($event['venue']) ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form-divider">
                    <span>Personal Information</span>
                </div>
                
                <div class="form-group">
                    <label for="student_name" class="form-label">
                        <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="student_name" name="student_name" class="form-control <?= isset($errors['student_name']) ? 'is-invalid' : '' ?>" 
                           placeholder="Enter your full name" required>
                    <?php if (isset($errors['student_name'])): ?>
                        <div class="invalid-feedback"><?= e($errors['student_name']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="student_email" class="form-label">
                            <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" id="student_email" name="student_email" class="form-control <?= isset($errors['student_email']) ? 'is-invalid' : '' ?>" 
                               placeholder="your.email@college.edu" required>
                        <div class="email-check-status" id="emailCheckStatus"></div>
                        <?php if (isset($errors['student_email'])): ?>
                            <div class="invalid-feedback"><?= e($errors['student_email']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i> Phone Number
                        </label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               placeholder="(123) 456-7890">
                    </div>
                </div>
                
                <div class="form-divider">
                    <span>Team Information (Optional)</span>
                </div>
                
                <div class="form-group">
                    <label for="team_name" class="form-label">
                        <i class="fas fa-users"></i> Team Name
                    </label>
                    <input type="text" id="team_name" name="team_name" class="form-control" 
                           placeholder="Enter team name if registering as part of a team">
                    <small class="form-text">Leave blank if registering as an individual participant</small>
                </div>
                
                <div class="form-actions">
                    <?php if ($event): ?>
                        <a href="index.php?route=sports.show&id=<?= $event['id'] ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Event
                        </a>
                    <?php else: ?>
                        <a href="index.php?route=sports.index" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check"></i> Complete Registration
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Sidebar Info -->
        <div class="registration-sidebar">
            <div class="sidebar-card">
                <h3><i class="fas fa-info-circle"></i> Registration Info</h3>
                <ul class="info-list-simple">
                    <li><i class="fas fa-check text-success"></i> Free registration</li>
                    <li><i class="fas fa-check text-success"></i> Instant confirmation</li>
                    <li><i class="fas fa-check text-success"></i> Email notification</li>
                    <li><i class="fas fa-check text-success"></i> Team registration available</li>
                </ul>
            </div>
            
            <div class="sidebar-card">
                <h3><i class="fas fa-question-circle"></i> Need Help?</h3>
                <p>If you have any questions about registration, contact our sports department.</p>
                <a href="mailto:sports@college.edu" class="btn btn-outline btn-block btn-sm">
                    <i class="fas fa-envelope"></i> Contact Support
                </a>
            </div>
            
            <div class="sidebar-card tips-card">
                <h3><i class="fas fa-lightbulb"></i> Quick Tips</h3>
                <ul>
                    <li>Use your official college email</li>
                    <li>Double-check event date and venue</li>
                    <li>Register early to secure your spot</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Event selection preview
const eventSelect = document.getElementById('sport_event_id');
if (eventSelect) {
    eventSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const infoDiv = document.getElementById('selectedEventInfo');
        
        if (this.value) {
            document.getElementById('selectedEventName').textContent = selected.textContent.split(' - ')[0];
            document.getElementById('selectedEventSport').textContent = selected.dataset.sport;
            document.getElementById('selectedEventDate').textContent = selected.dataset.date;
            document.getElementById('selectedEventVenue').textContent = selected.dataset.venue;
            infoDiv.style.display = 'block';
        } else {
            infoDiv.style.display = 'none';
        }
    });
}

// Check if already registered
let emailTimeout;
document.getElementById('student_email').addEventListener('input', function() {
    const email = this.value.trim();
    const eventIdInput = document.querySelector('input[name="sport_event_id"]');
    const eventIdSelect = document.getElementById('sport_event_id');
    const eventId = eventIdInput ? eventIdInput.value : (eventIdSelect ? eventIdSelect.value : null);
    const statusEl = document.getElementById('emailCheckStatus');
    
    clearTimeout(emailTimeout);
    
    if (!email || !eventId) {
        statusEl.innerHTML = '';
        return;
    }
    
    emailTimeout = setTimeout(async () => {
        if (!email.includes('@')) {
            statusEl.innerHTML = '';
            return;
        }
        
        try {
            const response = await fetch(`index.php?route=participants.checkRegistration&event_id=${eventId}&email=${encodeURIComponent(email)}`);
            const data = await response.json();
            
            if (data.registered) {
                statusEl.innerHTML = '<span class="text-warning"><i class="fas fa-exclamation-circle"></i> This email is already registered for this event</span>';
            } else {
                statusEl.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Email available for registration</span>';
            }
        } catch (error) {
            statusEl.innerHTML = '';
        }
    }, 500);
});

// Form validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    const name = document.getElementById('student_name').value.trim();
    const email = document.getElementById('student_email').value.trim();
    const eventIdInput = document.querySelector('input[name="sport_event_id"]');
    const eventIdSelect = document.getElementById('sport_event_id');
    const eventId = eventIdInput ? eventIdInput.value : (eventIdSelect ? eventIdSelect.value : null);
    
    if (!name || !email || !eventId) {
        e.preventDefault();
        showToast('Please fill in all required fields', 'error');
    }
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
