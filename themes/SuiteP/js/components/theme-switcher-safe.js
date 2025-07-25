/**
 * @fileoverview Safe Theme Switcher Implementation
 * 
 * JavaScript-only implementation that safely checks for user context
 * before initializing to prevent white screen errors.
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.1.0
 * @since 2024-01-16
 */

(function() {
    'use strict';

    // Safe initialization function
    function initThemeSwitcher() {
        // Check if we're in a valid context
        if (!document.body || document.readyState === 'loading') {
            // Wait for DOM to be ready
            document.addEventListener('DOMContentLoaded', initThemeSwitcher);
            return;
        }

        // Look for theme switcher container
        const container = document.getElementById('theme_switcher_container');
        if (!container) {
            return; // No container, nothing to do
        }

        // Get current theme safely
        let currentTheme = 'Dawn'; // Default
        
        // Try to detect theme from the page's stylesheet
        try {
            // Look for theme stylesheet link
            const styleLinks = document.querySelectorAll('link[rel="stylesheet"]');
            for (let link of styleLinks) {
                const href = link.href || '';
                // Check if this is a theme CSS file
                if (href.includes('themes/SuiteP/css/')) {
                    // Extract theme name from path
                    const themeMatch = href.match(/\/css\/(Dawn|Day|Dusk|Night|Noon)\//i);
                    if (themeMatch && themeMatch[1]) {
                        currentTheme = themeMatch[1];
                        break;
                    }
                }
            }
        } catch (e) {
            console.warn('Theme detection failed, using default:', e);
        }

        // Validate theme
        const validThemes = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
        if (!validThemes.includes(currentTheme)) {
            currentTheme = 'Dawn';
        }

        // Create theme switcher HTML
        const switcherHTML = `
            <div class="theme-switcher-safe">
                <button type="button" 
                        class="btn btn-default theme-btn-safe"
                        id="theme-switcher-button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-label="Switch Theme"
                        title="Change Theme">
                    <span class="suitepicon suitepicon-action-theme" aria-hidden="true"></span>
                    <span class="theme-label">${currentTheme}</span>
                    <span class="suitepicon suitepicon-action-caret" aria-hidden="true"></span>
                </button>
                <div class="theme-dropdown-safe" id="theme-dropdown" style="display: none;">
                    <ul class="theme-options-safe">
                        ${validThemes.map(theme => `
                            <li>
                                <button type="button"
                                        class="theme-option-safe ${theme === currentTheme ? 'active' : ''}"
                                        data-theme="${theme}">
                                    <span class="theme-preview-dot" style="background-color: ${getThemeColor(theme)}"></span>
                                    <span class="theme-name">${theme}</span>
                                    ${theme === currentTheme ? '<span class="suitepicon suitepicon-action-check"></span>' : ''}
                                </button>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            </div>
        `;

        // Insert HTML
        container.innerHTML = switcherHTML;

        // Add event listeners
        const button = document.getElementById('theme-switcher-button');
        const dropdown = document.getElementById('theme-dropdown');
        
        if (button && dropdown) {
            // Toggle dropdown
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = dropdown.style.display !== 'none';
                dropdown.style.display = isOpen ? 'none' : 'block';
                button.setAttribute('aria-expanded', !isOpen);
            });

            // Handle theme selection
            const themeButtons = dropdown.querySelectorAll('.theme-option-safe');
            themeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const selectedTheme = this.dataset.theme;
                    switchTheme(selectedTheme);
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdown.style.display = 'none';
                button.setAttribute('aria-expanded', 'false');
            });
        }
    }

    // Theme switching function
    function switchTheme(theme) {
        // Update CSS custom properties immediately
        updateCSSProperties(theme);
        
        // Update UI
        updateThemeUI(theme);
        
        // Save preference to server
        saveThemePreference(theme);
    }

    // Update CSS custom properties for instant theme change
    function updateCSSProperties(theme) {
        // This would be implemented based on your CSS custom properties
        document.documentElement.setAttribute('data-theme', theme.toLowerCase());
    }

    // Update theme UI elements
    function updateThemeUI(theme) {
        const label = document.querySelector('.theme-label');
        if (label) {
            label.textContent = theme;
        }

        // Update active state
        const options = document.querySelectorAll('.theme-option-safe');
        options.forEach(opt => {
            const isActive = opt.dataset.theme === theme;
            opt.classList.toggle('active', isActive);
            
            // Update checkmark
            const checkmark = opt.querySelector('.suitepicon-action-check');
            if (isActive && !checkmark) {
                opt.insertAdjacentHTML('beforeend', '<span class="suitepicon suitepicon-action-check"></span>');
            } else if (!isActive && checkmark) {
                checkmark.remove();
            }
        });
    }

    // Save theme preference via AJAX
    function saveThemePreference(theme) {
        try {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php?module=Users&action=SaveThemePreference&sugar_body_only=1', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        console.log('Theme saved successfully');
                        // Reload page to apply theme server-side
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    } else {
                        console.error('Failed to save theme preference');
                    }
                }
            };
            
            xhr.send('theme=' + encodeURIComponent(theme));
        } catch (e) {
            console.error('Error saving theme preference:', e);
        }
    }

    // Get theme preview color
    function getThemeColor(theme) {
        const colors = {
            'Dawn': '#778591',
            'Day': '#0B2E43',
            'Dusk': '#33577B',
            'Night': '#131820',
            'Noon': '#036DB7'
        };
        return colors[theme] || '#778591';
    }

    // Initialize when safe to do so
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initThemeSwitcher);
    } else {
        // DOM is already loaded
        setTimeout(initThemeSwitcher, 100); // Small delay to ensure everything is ready
    }

})(); 