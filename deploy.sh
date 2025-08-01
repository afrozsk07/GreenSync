#!/bin/bash

# GreenSync Deployment Script
echo "🚀 Starting GreenSync deployment..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the Laravel project root directory"
    exit 1
fi

# Build assets
echo "📦 Building frontend assets..."
npm run build

# Check if build was successful
if [ $? -ne 0 ]; then
    echo "❌ Error: Asset build failed"
    exit 1
fi

# Deploy to Vercel
echo "🚀 Deploying to Vercel..."
vercel --prod

echo "✅ Deployment completed!"
echo "🌐 Your app should be live at: https://your-project-name.vercel.app" 