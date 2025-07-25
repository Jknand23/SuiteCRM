{*
/**
 * @fileoverview Bootstrap 5 Namespaced System Demonstration Template
 * 
 * Demonstrates the Bootstrap 5 namespaced system working alongside existing
 * Bootstrap 3.3.7 components without conflicts. Shows practical usage patterns
 * for modern UI development while preserving existing functionality.
 * 
 * Key Features:
 * - Side-by-side comparison of Bootstrap 3.3.7 vs Bootstrap 5
 * - Theme integration demonstration
 * - Component isolation verification
 * - Migration strategy examples
 * 
 * Usage:
 * Include this template to test Bootstrap 5 namespaced components
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-16
 */
*}

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Bootstrap 5 Namespaced System Demo</h1>
            <p class="lead">
                Demonstration of modern Bootstrap 5 components working alongside 
                existing Bootstrap 3.3.7 without conflicts.
            </p>
        </div>
    </div>

    {* ================================================================== *}
    {* SECTION 1: SIDE-BY-SIDE COMPARISON *}
    {* ================================================================== *}
    
    <div class="row">
        <div class="col-12">
            <h2>Side-by-Side Comparison</h2>
            <p>Compare existing Bootstrap 3.3.7 components with modern Bootstrap 5 equivalents:</p>
        </div>
    </div>

    <div class="row">
        <!-- Bootstrap 3.3.7 Column -->
        <div class="col-md-6">
            <h3>Bootstrap 3.3.7 (Existing)</h3>
            
            <!-- Traditional Panel -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Traditional Panel</h3>
                </div>
                <div class="panel-body">
                    <p>This is a traditional Bootstrap 3.3.7 panel component.</p>
                    <button class="btn btn-primary">Primary Button</button>
                    <button class="btn btn-default">Default Button</button>
                    <button class="btn btn-success">Success Button</button>
                </div>
                <div class="panel-footer">
                    <small class="text-muted">Traditional footer</small>
                </div>
            </div>

            <!-- Traditional Grid -->
            <div class="panel panel-default">
                <div class="panel-heading">Grid System</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="well">Col 6</div>
                        </div>
                        <div class="col-xs-6">
                            <div class="well">Col 6</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 Namespaced Column -->
        <div class="col-md-6">
            <h3>Bootstrap 5 Namespaced (Modern)</h3>
            
            <!-- Modern Card -->
            <div class="bs5-card">
                <div class="bs5-card-header">
                    <div class="bs5-d-flex bs5-justify-content-between bs5-align-items-center">
                        <h5 class="bs5-card-title mb-0">Modern Card</h5>
                        <button class="bs5-btn bs5-btn-sm bs5-btn-outline-secondary">
                            Actions
                        </button>
                    </div>
                </div>
                <div class="bs5-card-body">
                    <p class="bs5-card-text">This is a modern Bootstrap 5 card component with enhanced styling.</p>
                    <div class="bs5-d-flex bs5-gap-2">
                        <button class="bs5-btn bs5-btn-primary">Primary</button>
                        <button class="bs5-btn bs5-btn-outline-primary">Outline</button>
                        <button class="bs5-btn bs5-btn-success">Success</button>
                    </div>
                </div>
                <div class="bs5-card-footer">
                    <small class="text-muted">Modern footer with flexbox</small>
                </div>
            </div>

            <!-- Modern Grid -->
            <div class="bs5-card bs5-mt-3">
                <div class="bs5-card-header">Modern Grid System</div>
                <div class="bs5-card-body">
                    <div class="bs5-container-fluid">
                        <div class="bs5-row bs5-g-3">
                            <div class="bs5-col-6">
                                <div class="bs5-card bs5-bg-light">
                                    <div class="bs5-card-body bs5-py-2">Col 6</div>
                                </div>
                            </div>
                            <div class="bs5-col-6">
                                <div class="bs5-card bs5-bg-light">
                                    <div class="bs5-card-body bs5-py-2">Col 6</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* ================================================================== *}
    {* SECTION 2: THEME INTEGRATION DEMO *}
    {* ================================================================== *}
    
    <div class="row bs5-mt-5">
        <div class="col-12">
            <h2>Theme Integration Demo</h2>
            <p>Bootstrap 5 components automatically adapt to SuiteCRM theme changes:</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="bs5-card bs5-theme-aware">
                <div class="bs5-card-header">
                    <h5 class="bs5-card-title">Theme-Aware Component</h5>
                </div>
                <div class="bs5-card-body">
                    <p class="bs5-card-text">
                        This card automatically adapts to theme changes. 
                        Switch themes using the theme selector to see it adapt.
                    </p>
                    
                    <div class="bs5-row bs5-g-2">
                        <div class="bs5-col-auto">
                            <button class="bs5-btn bs5-btn-primary">Primary (Theme Color)</button>
                        </div>
                        <div class="bs5-col-auto">
                            <button class="bs5-btn bs5-btn-success">Success (Theme Color)</button>
                        </div>
                        <div class="bs5-col-auto">
                            <button class="bs5-btn bs5-btn-warning">Warning (Theme Color)</button>
                        </div>
                        <div class="bs5-col-auto">
                            <button class="bs5-btn bs5-btn-danger">Danger (Theme Color)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* ================================================================== *}
    {* SECTION 3: MIXED USAGE DEMO *}
    {* ================================================================== *}
    
    <div class="row bs5-mt-5">
        <div class="col-12">
            <h2>Mixed Usage Demo</h2>
            <p>Existing and modern components working together seamlessly:</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Traditional panel with modern components inside -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="bs5-d-flex bs5-justify-content-between bs5-align-items-center">
                        <h3 class="panel-title">Traditional Panel + Modern Components</h3>
                        <div class="bs5-btn-group">
                            <button class="bs5-btn bs5-btn-sm bs5-btn-primary">Action 1</button>
                            <button class="bs5-btn bs5-btn-sm bs5-btn-outline-primary">Action 2</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <p>This demonstrates how modern Bootstrap 5 components can be used inside existing panels.</p>
                    
                    <!-- Modern grid inside traditional panel -->
                    <div class="bs5-row bs5-g-3">
                        <div class="bs5-col-md-4">
                            <div class="bs5-card">
                                <div class="bs5-card-body bs5-text-center">
                                    <h4 class="bs5-card-title">1,234</h4>
                                    <p class="bs5-card-text">Active Leads</p>
                                </div>
                            </div>
                        </div>
                        <div class="bs5-col-md-4">
                            <div class="bs5-card">
                                <div class="bs5-card-body bs5-text-center">
                                    <h4 class="bs5-card-title">567</h4>
                                    <p class="bs5-card-text">Conversions</p>
                                </div>
                            </div>
                        </div>
                        <div class="bs5-col-md-4">
                            <div class="bs5-card">
                                <div class="bs5-card-body bs5-text-center">
                                    <h4 class="bs5-card-title">89%</h4>
                                    <p class="bs5-card-text">Success Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* ================================================================== *}
    {* SECTION 4: ADVANCED COMPONENTS DEMO *}
    {* ================================================================== *}
    
    <div class="row bs5-mt-5">
        <div class="col-12">
            <h2>Advanced Components Demo</h2>
            <p>Modern Bootstrap 5 components with enhanced functionality:</p>
        </div>
    </div>

    <div class="row">
        <!-- Button Groups and Variants -->
        <div class="col-md-6">
            <div class="bs5-card">
                <div class="bs5-card-header">
                    <h5 class="bs5-card-title">Button Variants</h5>
                </div>
                <div class="bs5-card-body">
                    <h6>Solid Buttons</h6>
                    <div class="bs5-d-flex bs5-gap-2 bs5-mb-3 bs5-flex-wrap">
                        <button class="bs5-btn bs5-btn-primary">Primary</button>
                        <button class="bs5-btn bs5-btn-secondary">Secondary</button>
                        <button class="bs5-btn bs5-btn-success">Success</button>
                        <button class="bs5-btn bs5-btn-info">Info</button>
                        <button class="bs5-btn bs5-btn-warning">Warning</button>
                        <button class="bs5-btn bs5-btn-danger">Danger</button>
                    </div>

                    <h6>Outline Buttons</h6>
                    <div class="bs5-d-flex bs5-gap-2 bs5-mb-3 bs5-flex-wrap">
                        <button class="bs5-btn bs5-btn-outline-primary">Primary</button>
                        <button class="bs5-btn bs5-btn-outline-secondary">Secondary</button>
                        <button class="bs5-btn bs5-btn-outline-success">Success</button>
                        <button class="bs5-btn bs5-btn-outline-danger">Danger</button>
                    </div>

                    <h6>Button Sizes</h6>
                    <div class="bs5-d-flex bs5-gap-2 bs5-align-items-center bs5-flex-wrap">
                        <button class="bs5-btn bs5-btn-primary bs5-btn-sm">Small</button>
                        <button class="bs5-btn bs5-btn-primary">Default</button>
                        <button class="bs5-btn bs5-btn-primary bs5-btn-lg">Large</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accordion Demo -->
        <div class="col-md-6">
            <div class="bs5-card">
                <div class="bs5-card-header">
                    <h5 class="bs5-card-title">Accordion Component</h5>
                </div>
                <div class="bs5-card-body">
                    <div class="bs5-accordion">
                        <div class="bs5-accordion-item">
                            <h2 class="bs5-accordion-header">
                                <button class="bs5-accordion-button" type="button">
                                    Campaign Details
                                </button>
                            </h2>
                            <div class="bs5-accordion-collapse">
                                <div class="bs5-accordion-body">
                                    <strong>Campaign information</strong> with enhanced accordion functionality.
                                    This demonstrates the modern collapsible component.
                                </div>
                            </div>
                        </div>
                        <div class="bs5-accordion-item">
                            <h2 class="bs5-accordion-header">
                                <button class="bs5-accordion-button bs5-collapsed" type="button">
                                    Lead Statistics
                                </button>
                            </h2>
                            <div class="bs5-accordion-collapse">
                                <div class="bs5-accordion-body">
                                    Detailed statistics and metrics for campaign performance.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* ================================================================== *}
    {* SECTION 5: RESPONSIVE UTILITIES DEMO *}
    {* ================================================================== *}
    
    <div class="row bs5-mt-5">
        <div class="col-12">
            <h2>Responsive Utilities Demo</h2>
            <p>Modern responsive utilities for enhanced layouts:</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="bs5-card">
                <div class="bs5-card-header">
                    <h5 class="bs5-card-title">Responsive Layout Example</h5>
                </div>
                <div class="bs5-card-body">
                    <div class="bs5-row bs5-g-3">
                        <div class="bs5-col-12 bs5-col-md-8">
                            <div class="bs5-card bs5-bg-light">
                                <div class="bs5-card-body">
                                    <h6>Main Content Area</h6>
                                    <p>This area takes full width on mobile (12 cols) and 8 columns on medium screens and up.</p>
                                    
                                    <!-- Flexbox utilities demo -->
                                    <div class="bs5-d-flex bs5-justify-content-between bs5-align-items-center bs5-p-3 bg-white rounded">
                                        <span class="bs5-fw-bold">Flexbox Layout:</span>
                                        <div class="bs5-d-flex bs5-gap-2">
                                            <span class="badge badge-primary">Left</span>
                                            <span class="badge badge-success">Center</span>
                                            <span class="badge badge-info">Right</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bs5-col-12 bs5-col-md-4">
                            <div class="bs5-card bs5-bg-light">
                                <div class="bs5-card-body">
                                    <h6>Sidebar</h6>
                                    <p>This sidebar stacks below on mobile and shows beside main content on larger screens.</p>
                                    
                                    <!-- Display utilities demo -->
                                    <div class="bs5-d-none bs5-d-md-block">
                                        <small class="text-muted">Visible only on medium screens and up</small>
                                    </div>
                                    <div class="bs5-d-block bs5-d-md-none">
                                        <small class="text-muted">Visible only on small screens</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* ================================================================== *}
    {* SECTION 6: IMPLEMENTATION NOTES *}
    {* ================================================================== *}
    
    <div class="row bs5-mt-5 bs5-mb-5">
        <div class="col-12">
            <div class="bs5-card">
                <div class="bs5-card-header">
                    <h5 class="bs5-card-title">Implementation Notes</h5>
                </div>
                <div class="bs5-card-body">
                    <div class="bs5-row">
                        <div class="bs5-col-md-6">
                            <h6>✅ What Works</h6>
                            <ul>
                                <li>Complete isolation between Bootstrap versions</li>
                                <li>Automatic theme integration</li>
                                <li>Modern flexbox layouts</li>
                                <li>Enhanced button states and variants</li>
                                <li>Responsive utilities</li>
                                <li>Side-by-side usage</li>
                            </ul>
                        </div>
                        <div class="bs5-col-md-6">
                            <h6>🎯 Migration Strategy</h6>
                            <ul>
                                <li><strong>Phase 1:</strong> Use Bootstrap 5 for new components only</li>
                                <li><strong>Phase 2:</strong> Enhance existing components gradually</li>
                                <li><strong>Phase 3:</strong> Template-level enhancements with fallbacks</li>
                                <li><strong>Always:</strong> Use .bs5- prefix for all new classes</li>
                                <li><strong>Testing:</strong> Verify theme switching and responsive behavior</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{* JavaScript for interactive demo (optional) *}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Demo accordion functionality
    const accordionButtons = document.querySelectorAll('.bs5-accordion-button');
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const collapse = this.parentNode.nextElementSibling;
            const isExpanded = !this.classList.contains('bs5-collapsed');
            
            // Toggle collapsed state
            this.classList.toggle('bs5-collapsed', isExpanded);
            
            // Toggle accordion content
            if (collapse) {
                collapse.style.display = isExpanded ? 'none' : 'block';
            }
        });
    });
    
    console.log('Bootstrap 5 Namespaced Demo: Interactive features loaded');
    console.log('Available classes: .bs5-container, .bs5-row, .bs5-col-*, .bs5-btn, .bs5-card');
});
</script> 