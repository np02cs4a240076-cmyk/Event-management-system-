<?php
$title = 'Sports Events';
ob_start();
?>

<div class="page-header">
    <div class="container">
        <div class="page-header-content">
            <div>
                <h1><i class="fas fa-calendar-alt"></i> Sports Events</h1>
                <p>Discover and join exciting college sports events</p>
            </div>
            <?php if ($auth_user): ?>
                <a href="index.php?route=sports.create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Event
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">
    <!-- Search Section -->
    <div class="search-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" class="search-input" 
                   placeholder="Search events by name, sport, or venue...">
            <button class="search-clear" id="searchClear" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">
                <i class="fas fa-th"></i> All
            </button>
            <?php foreach ($sports as $sport): ?>
                <button class="filter-btn" data-filter="<?= strtolower(e($sport)) ?>">
                    <?= e($sport) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="events-grid" id="eventsGrid">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card" data-sport="<?= strtolower(e($event['sport_name'])) ?>">
                    <div class="event-card-header <?= getSportBgClass($event['sport_name']) ?>">
                        <div class="event-sport-badge">
                            <i class="<?= getSportIcon($event['sport_name']) ?>"></i>
                            <?= e($event['sport_name']) ?>
                        </div>
                        <div class="event-status status-<?= e($event['status']) ?>">
                            <?= ucfirst(e($event['status'])) ?>
                        </div>
                    </div>
                    
                    <div class="event-card-body">
                        <h3 class="event-title"><?= e($event['event_title']) ?></h3>
                        
                        <div class="event-meta">
                            <div class="event-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span><?= date('F d, Y', strtotime($event['event_date'])) ?></span>
                            </div>
                            <div class="event-meta-item">
                                <i class="fas fa-clock"></i>
                                <span><?= date('h:i A', strtotime($event['event_time'])) ?></span>
                            </div>
                            <div class="event-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= e($event['venue']) ?></span>
                            </div>
                        </div>
                        
                        <div class="event-stats">
                            <div class="event-stat">
                                <span class="stat-value"><?= $event['booking_count'] ?></span>
                                <span class="stat-label">Booked</span>
                            </div>
                            <div class="event-stat">
                                <span class="stat-value"><?= $event['total_capacity'] - $event['booking_count'] ?></span>
                                <span class="stat-label">Available</span>
                            </div>
                            <div class="event-stat">
                                <span class="stat-value"><?= $event['team_limit'] ?></span>
                                <span class="stat-label">Teams</span>
                            </div>
                        </div>
                        
                        <div class="capacity-bar">
                            <div class="capacity-fill" style="width: <?= ($event['booking_count'] / max($event['total_capacity'], 1)) * 100 ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="event-card-footer">
                        <a href="index.php?route=sports.show&id=<?= $event['id'] ?>" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <?php if ($auth_user): ?>
                            <a href="index.php?route=sports.edit&id=<?= $event['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state-full">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h2>No Events Found</h2>
                <p>There are no sports events scheduled yet.</p>
                <?php if ($auth_user): ?>
                    <a href="index.php?route=sports.create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Event
                    </a>
                <?php else: ?>
                    <a href="index.php?route=auth.login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login to Create Event
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Search Results Container -->
    <div class="search-results" id="searchResults" style="display: none;">
        <div class="search-results-header">
            <h3><i class="fas fa-search"></i> Search Results: <span id="searchQuery"></span></h3>
            <span id="resultsCount"></span>
        </div>
        <div class="events-grid" id="searchResultsGrid"></div>
    </div>
    
    <!-- No Results State -->
    <div class="no-results" id="noResults" style="display: none;">
        <div class="empty-state-icon">
            <i class="fas fa-search"></i>
        </div>
        <h2>No Results Found</h2>
        <p>Try adjusting your search or filter criteria</p>
        <button class="btn btn-outline" onclick="clearSearch()">
            <i class="fas fa-times"></i> Clear Search
        </button>
    </div>
</div>

<script>
// Live Search with AJAX
const searchInput = document.getElementById('searchInput');
const searchClear = document.getElementById('searchClear');
const eventsGrid = document.getElementById('eventsGrid');
const searchResults = document.getElementById('searchResults');
const searchResultsGrid = document.getElementById('searchResultsGrid');
const noResults = document.getElementById('noResults');
let searchTimeout;

searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    clearTimeout(searchTimeout);
    
    searchClear.style.display = query ? 'flex' : 'none';
    
    if (!query) {
        eventsGrid.style.display = 'grid';
        searchResults.style.display = 'none';
        noResults.style.display = 'none';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        performSearch(query);
    }, 300);
});

searchClear.addEventListener('click', clearSearch);

async function performSearch(query) {
    try {
        const response = await fetch(`index.php?route=sports.search&q=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        eventsGrid.style.display = 'none';
        
        if (data.events && data.events.length > 0) {
            searchResults.style.display = 'block';
            noResults.style.display = 'none';
            
            document.getElementById('searchQuery').textContent = `"${query}"`;
            document.getElementById('resultsCount').textContent = `${data.count} event(s) found`;
            
            renderEvents(data.events);
        } else {
            searchResults.style.display = 'none';
            noResults.style.display = 'flex';
        }
    } catch (error) {
        console.error('Search error:', error);
    }
}

function renderEvents(events) {
    const html = events.map(event => {
        const availableSeats = event.total_capacity - event.booking_count;
        const capacityPercent = (event.booking_count / Math.max(event.total_capacity, 1)) * 100;
        
        return `
            <div class="event-card" data-sport="${event.sport_name.toLowerCase()}">
                <div class="event-card-header bg-gradient-primary">
                    <div class="event-sport-badge">
                        <i class="fas fa-trophy"></i>
                        ${escapeHtml(event.sport_name)}
                    </div>
                    <div class="event-status status-${event.status}">
                        ${event.status.charAt(0).toUpperCase() + event.status.slice(1)}
                    </div>
                </div>
                
                <div class="event-card-body">
                    <h3 class="event-title">${escapeHtml(event.event_title)}</h3>
                    
                    <div class="event-meta">
                        <div class="event-meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>${formatDate(event.event_date)}</span>
                        </div>
                        <div class="event-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${escapeHtml(event.venue)}</span>
                        </div>
                    </div>
                    
                    <div class="event-stats">
                        <div class="event-stat">
                            <span class="stat-value">${event.booking_count}</span>
                            <span class="stat-label">Booked</span>
                        </div>
                        <div class="event-stat">
                            <span class="stat-value">${availableSeats}</span>
                            <span class="stat-label">Available</span>
                        </div>
                    </div>
                    
                    <div class="capacity-bar">
                        <div class="capacity-fill" style="width: ${capacityPercent}%"></div>
                    </div>
                </div>
                
                <div class="event-card-footer">
                    <a href="index.php?route=sports.show&id=${event.id}" class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            </div>
        `;
    }).join('');
    
    searchResultsGrid.innerHTML = html;
}

function clearSearch() {
    searchInput.value = '';
    searchClear.style.display = 'none';
    eventsGrid.style.display = 'grid';
    searchResults.style.display = 'none';
    noResults.style.display = 'none';
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === 'all') btn.classList.add('active');
    });
    document.querySelectorAll('.event-card').forEach(card => {
        card.style.display = 'flex';
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
}

// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        searchInput.value = '';
        searchClear.style.display = 'none';
        eventsGrid.style.display = 'grid';
        searchResults.style.display = 'none';
        noResults.style.display = 'none';
        
        const cards = document.querySelectorAll('#eventsGrid .event-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            if (filter === 'all' || card.dataset.sport === filter) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        if (visibleCount === 0) {
            noResults.style.display = 'flex';
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
