#!/bin/bash
# SuiteCRM Theme Compiler Wrapper
# Usage: ./compile-themes.sh [theme_name|all|list]
#
# Examples:
#   ./compile-themes.sh Dawn      # Compile Dawn theme
#   ./compile-themes.sh all       # Compile all themes  
#   ./compile-themes.sh list      # List available themes
#   ./compile-themes.sh           # Compile all themes (default)

THEME=${1:-all}

echo "🎨 SuiteCRM Theme Compiler"
echo "=========================="

# Check if Docker is running
if ! docker ps | grep -q suitecrm_app; then
    echo "❌ Error: suitecrm_app container not running"
    echo "   Start with: docker-compose up -d"
    exit 1
fi

# Run compilation
echo "📦 Compiling theme(s): $THEME"
echo ""

docker exec suitecrm_app php compile-themes.php "$THEME"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Theme compilation completed successfully!"
    echo "📁 Output location: themes/SuiteP/css/*/style.css"
else
    echo ""
    echo "❌ Theme compilation failed!"
    echo "💡 Check SCSS syntax and ensure all variables are defined"
    exit 1
fi 