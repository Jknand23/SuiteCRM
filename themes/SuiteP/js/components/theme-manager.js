/**
 * @fileoverview Alpine.js Theme Management Component
 * 
 * Progressive enhancement component that provides instant theme switching capabilities
 * while maintaining full backward compatibility with existing SuiteCRM theme system.
 * Reads from existing user preferences and syncs changes with server-side storage.
 * 
 * Key Features:
 * - Instant visual theme switching using CSS custom properties
 * - Integration with existing UserPreference system
 * - Backward compatibility with server-side theme switching
 * - Progressive enhancement (works without JavaScript)
 * - Persistent user preferences via AJAX
 * 
 * Dependencies:
 * - Alpine.js 3.x for reactivity
 * - CSS custom properties in compiled themes
 * - Existing SuiteCRM user preference system
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

/**
 * Theme Manager Alpine.js Component
 * 
 * Manages theme switching with instant visual feedback and server synchronization.
 * Integrates with existing SuiteCRM theme preference system for seamless compatibility.
 */
function themeManager() {
    return {
        // Current theme state (initialized from server)
        currentTheme: window.suiteThemePreference || 'Dawn',
        availableThemes: ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'],
        
        // UI state
        isLoading: false,
        isDropdownOpen: false,
        hasError: false,
        errorMessage: '',
        
        // Theme metadata
        themeMetadata: {
            'Dawn': {
                name: 'Dawn',
                description: 'Light, professional theme',
                preview: '#778591'
            },
            'Day': {
                name: 'Day', 
                description: 'Bright, energetic theme',
                preview: '#5A8DEE'
            },
            'Dusk': {
                name: 'Dusk',
                description: 'Warm, comfortable theme', 
                preview: '#FF6B6B'
            },
            'Night': {
                name: 'Night',
                description: 'Dark, elegant theme',
                preview: '#2C3E50'
            },
            'Noon': {
                name: 'Noon',
                description: 'Clean, minimal theme',
                preview: '#28A745'
            }
        },

        /**
         * Component initialization
         * Reads current theme from existing SuiteCRM preferences
         */
        init() {
            // Read theme from existing SuiteCRM session/preferences
            this.loadCurrentTheme();
            
            // Apply initial theme properties
            this.applyThemeProperties(this.currentTheme);
            
            // Listen for external theme changes (e.g., from user preferences form)
            this.setupEventListeners();
            
            console.log('Theme Manager initialized with theme:', this.currentTheme);
        },

        /**
         * Load current theme from existing SuiteCRM preference system
         */
        loadCurrentTheme() {
            // Try multiple sources in order of preference
            if (window.suiteThemePreference) {
                this.currentTheme = window.suiteThemePreference;
            } else if (document.querySelector('meta[name="suite-theme"]')) {
                this.currentTheme = document.querySelector('meta[name="suite-theme"]').content;
            } else if (window.SUGAR && window.SUGAR.themes && window.SUGAR.themes.current) {
                this.currentTheme = window.SUGAR.themes.current;
            }
            
            // Validate theme exists
            if (!this.availableThemes.includes(this.currentTheme)) {
                this.currentTheme = 'Dawn'; // fallback
            }
        },

        /**
         * Switch theme with instant visual feedback and server sync
         * 
         * @param {string} themeName - Name of theme to switch to
         */
        async switchTheme(themeName) {
            if (!this.availableThemes.includes(themeName) || this.isLoading) {
                return;
            }

            const previousTheme = this.currentTheme;
            this.isLoading = true;
            this.hasError = false;

            try {
                // STEP 1: Apply theme instantly using CSS custom properties
                this.applyThemeProperties(themeName);
                this.currentTheme = themeName;

                // STEP 2: Sync with SuiteCRM server (preserves existing system)
                await this.syncThemeWithServer(themeName);

                // STEP 3: Update any existing UI elements that depend on theme
                this.notifyThemeChange(themeName);

                this.closeDropdown();
            } catch (error) {
                console.error('Theme switch failed:', error);
                
                // Rollback visual changes on error
                this.applyThemeProperties(previousTheme);
                this.currentTheme = previousTheme;
                
                this.hasError = true;
                this.errorMessage = 'Failed to save theme preference. Please try again.';
            } finally {
                this.isLoading = false;
            }
        },

        /**
         * Apply theme CSS custom properties for instant visual feedback
         * 
         * @param {string} themeName - Theme to apply
         */
        applyThemeProperties(themeName) {
            // Load theme-specific CSS custom properties from pre-compiled CSS
            this.loadThemeCSS(themeName);
            
            // Update any dynamic theme classes
            document.documentElement.setAttribute('data-suite-theme', themeName.toLowerCase());
            
            // Trigger CSS custom property updates (for browsers that need it)
            document.documentElement.style.setProperty('--theme-name', `"${themeName}"`);
        },

        /**
         * Load theme CSS custom properties from compiled CSS files
         * 
         * @param {string} themeName - Theme name to load
         */
        loadThemeCSS(themeName) {
            // Check if theme CSS is already loaded
            const existingStylesheet = document.querySelector(`link[data-theme="${themeName}"]`);
            
            if (!existingStylesheet) {
                // Dynamically load theme CSS if not already present
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = `themes/SuiteP/css/${themeName}/style.css`;
                link.setAttribute('data-theme', themeName);
                link.onload = () => {
                    console.log(`Theme CSS loaded: ${themeName}`);
                };
                document.head.appendChild(link);
            }
        },

        /**
         * Sync theme change with SuiteCRM server using existing preference system
         * 
         * @param {string} themeName - Theme to save on server
         */
        async syncThemeWithServer(themeName) {
            const formData = new FormData();
            formData.append('preference_name', 'subtheme');
            formData.append('preference_value', themeName);
            formData.append('category', 'global');

            const response = await fetch('index.php?module=UserPreferences&action=SaveThemePreference', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`Server responded with ${response.status}: ${response.statusText}`);
            }

            // Parse JSON response
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.error || 'Failed to save theme preference');
            }

            // Update window global for other components
            window.suiteThemePreference = themeName;
        },

        /**
         * Notify other components of theme change
         * 
         * @param {string} themeName - New theme name
         */
        notifyThemeChange(themeName) {
            // Dispatch custom event for other components
            window.dispatchEvent(new CustomEvent('suiteThemeChanged', {
                detail: {
                    theme: themeName,
                    timestamp: Date.now()
                }
            }));

            // Update any legacy elements that might depend on theme
            if (window.SUGAR && window.SUGAR.themes) {
                window.SUGAR.themes.current = themeName;
            }
        },

        /**
         * Setup event listeners for external theme changes
         */
        setupEventListeners() {
            // Listen for theme changes from user preferences form
            document.addEventListener('suitePreferenceChanged', (event) => {
                if (event.detail.preference === 'subtheme') {
                    this.currentTheme = event.detail.value;
                    this.applyThemeProperties(this.currentTheme);
                }
            });
        },

        /**
         * Toggle theme dropdown
         */
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },

        /**
         * Close theme dropdown
         */
        closeDropdown() {
            this.isDropdownOpen = false;
        },

        /**
         * Get theme metadata for display
         * 
         * @param {string} themeName - Theme name
         * @returns {Object} Theme metadata
         */
        getThemeInfo(themeName) {
            return this.themeMetadata[themeName] || {
                name: themeName,
                description: 'Custom theme',
                preview: '#000000'
            };
        },

        /**
         * Check if theme is currently active
         * 
         * @param {string} themeName - Theme to check
         * @returns {boolean} True if theme is active
         */
        isThemeActive(themeName) {
            return this.currentTheme === themeName;
        },

        /**
         * Clear error state
         */
        clearError() {
            this.hasError = false;
            this.errorMessage = '';
        }
    };
} 