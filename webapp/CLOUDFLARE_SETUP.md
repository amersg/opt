# Cloudflare Tunnel Setup for WordPress

This guide will help you set up a Cloudflare tunnel to expose your WordPress site running on `wp.internal.lan` to the internet via `amer-alsabbagh.de`.

## Prerequisites

1. **Cloudflare Account**: You need a Cloudflare account with the domain `amer-alsabbagh.de` added
2. **cloudflared CLI**: Install the cloudflared CLI tool
3. **Firewall**: Port 7844/UDP should be open (already configured on your OPNsense)

## Quick Setup

Run the automated setup script:

```bash
cd /opt/webapp
./setup-cloudflared.sh
```

## Manual Setup (Alternative)

If you prefer to set up manually:

### 1. Install cloudflared CLI

```bash
# Ubuntu/Debian
sudo apt install cloudflared

# Or download from GitHub
wget https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb
sudo dpkg -i cloudflared-linux-amd64.deb
```

### 2. Login to Cloudflare

```bash
cloudflared tunnel login
```

This will open a browser window for authentication.

### 3. Create Tunnel

```bash
cloudflared tunnel create amer-alsabbagh-tunnel
```

### 4. Configure DNS

```bash
cloudflared tunnel route dns amer-alsabbagh-tunnel amer-alsabbagh.de
```

### 5. Get Tunnel Token

```bash
cloudflared tunnel token amer-alsabbagh-tunnel > /opt/webapp/cloudflared/credentials.json
```

### 6. Create Environment File

Create `/opt/webapp/.env` with your tunnel token:

```env
# WordPress Database Configuration
WORDPRESS_DB_HOST=db
WORDPRESS_DB_USER=wordpress
WORDPRESS_DB_PASSWORD=your_db_password_here
WORDPRESS_DB_NAME=wordpress

# Cloudflare Tunnel Configuration
CLOUDFLARE_TUNNEL_TOKEN=your_tunnel_token_here
```

### 7. Start Services

```bash
cd /opt/webapp
docker-compose up -d
```

## Configuration Files

### Docker Compose
The `docker-compose.yml` now includes a `cloudflared` service that:
- Uses the latest cloudflared image
- Connects to the `webapp_internal` network
- Exposes port 7844/UDP for the tunnel
- Uses your tunnel credentials

### Cloudflared Config
The `/opt/webapp/cloudflared/config.yml` file configures:
- Tunnel name: `amer-alsabbagh-tunnel`
- Domain routing: `amer-alsabbagh.de` and `www.amer-alsabbagh.de`
- Backend service: `http://wp-nginx:80`
- Proper headers for WordPress

### Nginx Configuration
The nginx configuration has been updated to:
- Accept connections from cloudflared container
- Use `CF-Connecting-IP` header for real client IPs
- Remove the edge token requirement
- Support both domains (`amer-alsabbagh.de` and `wp.internal.lan`)

## Monitoring

### View Tunnel Logs
```bash
docker-compose logs -f cloudflared
```

### Check Tunnel Status
```bash
docker-compose ps cloudflared
```

### Test Connectivity
```bash
curl -I https://amer-alsabbagh.de
```

## Troubleshooting

### Common Issues

1. **Tunnel not connecting**
   - Check if port 7844/UDP is open on your firewall
   - Verify tunnel token is correct in `.env` file
   - Check cloudflared logs: `docker-compose logs cloudflared`

2. **Domain not resolving**
   - Verify DNS record exists in Cloudflare dashboard
   - Check if domain is proxied (orange cloud) in Cloudflare

3. **502 Bad Gateway**
   - Ensure nginx container is running: `docker-compose ps wp-nginx`
   - Check nginx logs: `docker-compose logs wp-nginx`

4. **WordPress redirects to local domain**
   - Update WordPress site URL in database or wp-config.php
   - Set `WP_HOME` and `WP_SITEURL` to `https://amer-alsabbagh.de`

### Useful Commands

```bash
# Restart tunnel service
docker-compose restart cloudflared

# View all logs
docker-compose logs

# Check network connectivity
docker-compose exec cloudflared ping wp-nginx

# Test tunnel connectivity
cloudflared tunnel info amer-alsabbagh-tunnel
```

## Security Notes

- The tunnel provides encrypted communication between Cloudflare and your server
- Your WordPress site is only accessible through the tunnel (not directly from internet)
- Cloudflare provides DDoS protection and SSL termination
- Consider enabling Cloudflare's security features in the dashboard

## Next Steps

1. Configure WordPress to use the public domain
2. Set up SSL certificates (handled by Cloudflare)
3. Configure Cloudflare security settings
4. Set up monitoring and alerts
5. Consider enabling Cloudflare caching for better performance
