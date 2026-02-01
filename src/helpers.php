<?php
/**
 * Global helper functions for views
 * These functions are available in all view files
 */

if (!function_exists('e')) {
    /**
     * Escape HTML special characters
     */
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('getSportIcon')) {
    /**
     * Get Font Awesome icon class for a sport
     */
    function getSportIcon($sport) {
        $icons = [
            'Basketball' => 'fas fa-basketball-ball',
            'Football' => 'fas fa-football-ball',
            'Soccer' => 'fas fa-futbol',
            'Volleyball' => 'fas fa-volleyball-ball',
            'Cricket' => 'fas fa-baseball-ball',
            'Tennis' => 'fas fa-table-tennis',
            'Baseball' => 'fas fa-baseball-ball',
            'Swimming' => 'fas fa-swimmer',
            'Athletics' => 'fas fa-running',
            'Badminton' => 'fas fa-feather',
        ];
        return $icons[$sport] ?? 'fas fa-trophy';
    }
}

if (!function_exists('getSportBgClass')) {
    /**
     * Get background gradient class for a sport
     */
    function getSportBgClass($sport) {
        $colors = [
            'Basketball' => 'bg-gradient-orange',
            'Football' => 'bg-gradient-brown',
            'Soccer' => 'bg-gradient-green',
            'Volleyball' => 'bg-gradient-yellow',
            'Cricket' => 'bg-gradient-red',
            'Tennis' => 'bg-gradient-lime',
            'Baseball' => 'bg-gradient-red',
            'Swimming' => 'bg-gradient-blue',
            'Athletics' => 'bg-gradient-purple',
            'Badminton' => 'bg-gradient-teal',
        ];
        return $colors[$sport] ?? 'bg-gradient-primary';
    }
}

if (!function_exists('getSportColorClass')) {
    /**
     * Get color class for a sport
     */
    function getSportColorClass($sport) {
        $colors = [
            'Basketball' => 'color-orange',
            'Football' => 'color-brown',
            'Soccer' => 'color-green',
            'Volleyball' => 'color-yellow',
            'Cricket' => 'color-red',
            'Tennis' => 'color-lime',
            'Baseball' => 'color-red',
            'Swimming' => 'color-blue',
            'Athletics' => 'color-purple',
            'Badminton' => 'color-teal',
        ];
        return $colors[$sport] ?? 'color-primary';
    }
}
