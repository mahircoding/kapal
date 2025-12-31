#!/bin/bash

echo "=== Storage Troubleshooting Script ==="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Not in Laravel root directory"
    echo "Please run this script from your Laravel project root"
    exit 1
fi

echo "✓ Laravel project detected"
echo ""

# 1. Check storage link
echo "1. Checking storage link..."
if [ -L "public/storage" ]; then
    echo "✓ Storage link exists"
    ls -la public/storage
else
    echo "❌ Storage link NOT found"
    echo "Creating storage link..."
    php artisan storage:link
fi
echo ""

# 2. Check if files exist
echo "2. Checking uploaded files..."
if [ -d "storage/app/public/documents" ]; then
    echo "✓ Documents directory exists"
    echo "Files in documents:"
    find storage/app/public/documents -type f | head -10
else
    echo "❌ Documents directory NOT found"
fi
echo ""

# 3. Check permissions
echo "3. Checking permissions..."
echo "Storage directory:"
ls -ld storage/
echo ""
echo "Public storage:"
if [ -d "public/storage" ]; then
    ls -ld public/storage
fi
echo ""

# 4. Check specific KTP/KK directories
echo "4. Checking KTP/KK directories..."
for dir in ktps kks; do
    if [ -d "storage/app/public/documents/$dir" ]; then
        echo "✓ $dir directory exists"
        count=$(find storage/app/public/documents/$dir -type f | wc -l)
        echo "  Files: $count"
        ls -la storage/app/public/documents/$dir | head -5
    else
        echo "❌ $dir directory NOT found"
    fi
done
echo ""

# 5. Test file access
echo "5. Testing file accessibility..."
test_file=$(find storage/app/public/documents/ktps -type f -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" | head -1)
if [ -n "$test_file" ]; then
    echo "Test file: $test_file"
    echo "Permissions: $(ls -l $test_file)"
    echo "Readable: $([ -r "$test_file" ] && echo "Yes" || echo "No")"
else
    echo "No image files found in ktps directory"
fi
echo ""

# 6. Recommendations
echo "=== Recommendations ==="
echo ""
echo "If files are forbidden, run these commands:"
echo ""
echo "# Fix permissions"
echo "chmod -R 775 storage"
echo "chmod -R 775 bootstrap/cache"
echo ""
echo "# Fix ownership (replace 'www-data' with your web server user)"
echo "chown -R www-data:www-data storage"
echo "chown -R www-data:www-data bootstrap/cache"
echo ""
echo "# Recreate storage link"
echo "rm public/storage"
echo "php artisan storage:link"
echo ""
echo "# For cPanel/shared hosting, use your username:"
echo "chown -R username:username storage"
echo ""
