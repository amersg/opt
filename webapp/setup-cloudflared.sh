#!/bin/bash

# Cloudflare Tunnel Setup Script for WordPress
# This script helps you set up the cloudflared tunnel for amer-alsabbagh.de

echo "=== Cloudflare Tunnel Setup for WordPress ==="
echo "Domain: amer-alsabbagh.de"
echo "Local domain: wp.internal.lan"
echo ""

# Check if cloudflared is available
if ! command -v cloudflared &> /dev/null; then
    echo "❌ cloudflared is not installed. Please install it first:"
    echo "   Ubuntu/Debian: sudo apt install cloudflared"
    echo "   Or download from: https://github.com/cloudflare/cloudflared/releases"
    exit 1
fi

echo "✅ cloudflared is installed"

# Check if user is logged in to Cloudflare
if ! cloudflared tunnel list &> /dev/null; then
    echo ""
    echo "🔐 Please login to Cloudflare first:"
    echo "   Run: cloudflared tunnel login"
    echo "   This will open a browser window for authentication"
    echo ""
    read -p "Press Enter after you've completed the login..."
fi

echo ""
echo "📝 Creating tunnel 'amer-alsabbagh-tunnel'..."

# Create the tunnel
TUNNEL_ID=$(cloudflared tunnel create amer-alsabbagh-tunnel --output json | jq -r '.id')

if [ "$TUNNEL_ID" = "null" ] || [ -z "$TUNNEL_ID" ]; then
    echo "❌ Failed to create tunnel. Please check your Cloudflare authentication."
    exit 1
fi

echo "✅ Tunnel created with ID: $TUNNEL_ID"

# Save credentials
echo "💾 Saving tunnel credentials..."
cloudflared tunnel token $TUNNEL_ID > /opt/webapp/cloudflared/credentials.json

echo "✅ Credentials saved to /opt/webapp/cloudflared/credentials.json"

# Create CNAME record
echo ""
echo "🌐 Setting up DNS record..."
cloudflared tunnel route dns amer-alsabbagh-tunnel amer-alsabbagh.de

echo "✅ DNS record created for amer-alsabbagh.de"

# Create .env file with tunnel token
echo ""
echo "📄 Creating environment file..."

# Extract tunnel token
TUNNEL_TOKEN=$(cat /opt/webapp/cloudflared/credentials.json)

cat > /opt/webapp/.env << EOF
# WordPress Database Configuration
WORDPRESS_DB_HOST=db
WORDPRESS_DB_USER=wordpress
WORDPRESS_DB_PASSWORD=your_db_password_here
WORDPRESS_DB_NAME=wordpress

# Cloudflare Tunnel Configuration
CLOUDFLARE_TUNNEL_TOKEN=$TUNNEL_TOKEN
EOF

echo "✅ Environment file created at /opt/webapp/.env"
echo ""
echo "🔧 Next steps:"
echo "1. Update the database password in /opt/webapp/.env"
echo "2. Run: docker-compose up -d"
echo "3. Your WordPress site will be available at: https://amer-alsabbagh.de"
echo ""
echo "📊 To monitor the tunnel:"
echo "   docker-compose logs -f cloudflared"
echo ""
echo "🎉 Setup complete!"
