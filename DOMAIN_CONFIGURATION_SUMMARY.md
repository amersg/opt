# Domain Configuration Summary for amer-alsabbagh.de

## Current Status: ✅ Tunnel Working, ⚠️ WordPress URL Configuration Needed

Your cloudflared tunnel is working correctly, but WordPress needs database URL updates to work with the public domain.

## Configuration Files Updated:

### 1. ✅ `/opt/webapp/.env` - WordPress Environment Variables
```bash
WP_HOME=https://amer-alsabbagh.de
WP_SITEURL=https://amer-alsabbagh.de
WP_ENV=production
```

### 2. ✅ `/opt/webapp/cloudflared/config.yml` - Cloudflared Tunnel Config
```yaml
tunnel: 31887844-98c1-4cca-972c-a9e519d5c302
credentials-file: /etc/cloudflared/credentials.json
protocol: http2

ingress:
  - hostname: amer-alsabbagh.de
    service: http://edge-nginx:80
  - hostname: www.amer-alsabbagh.de
    service: http://edge-nginx:80
  - service: http_status:404
```

### 3. ✅ `/opt/edge/conf/site.conf` - Edge Nginx Configuration
```nginx
server {
    listen 80 default_server;
    server_name amer-alsabbagh.de www.amer-alsabbagh.de;
    
    location / {
        proxy_set_header Host amer-alsabbagh.de;
        proxy_set_header X-Edge-Token "s3cr3t-long-random-string";
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host amer-alsabbagh.de;
        proxy_pass http://wp_backend;
    }
}
```

### 4. ✅ `/opt/webapp/nginx/site.conf` - WordPress Nginx Configuration
```nginx
server {
  listen 80 default_server;
  server_name amer-alsabbagh.de www.amer-alsabbagh.de wp.internal.lan;
  # ... rest of configuration
}
```

## ⚠️ Required: WordPress Database URL Update

The WordPress database still contains the old URLs. You need to update the database directly:

### Option 1: Using wp-cli (if available)
```bash
cd /opt/webapp
docker compose exec wp-php wp option update home 'https://amer-alsabbagh.de'
docker compose exec wp-php wp option update siteurl 'https://amer-alsabbagh.de'
```

### Option 2: Direct Database Update
Connect to your MySQL database (192.168.30.2:3306) and run:
```sql
UPDATE wp_options SET option_value = 'https://amer-alsabbagh.de' WHERE option_name = 'home';
UPDATE wp_options SET option_value = 'https://amer-alsabbagh.de' WHERE option_name = 'siteurl';
```

### Option 3: WordPress Admin Panel
1. Access WordPress admin via https://amer-alsabbagh.de/wp-admin/
2. Go to Settings → General
3. Update WordPress Address (URL) and Site Address (URL) to https://amer-alsabbagh.de

## Current Architecture:

```
Internet → Cloudflare → Cloudflared Tunnel → Edge Nginx → WordPress Nginx → WordPress PHP
```

All components are properly configured and communicating. The only remaining issue is the WordPress database URLs.

## Files Structure:
```
/opt/
├── edge/
│   ├── docker-compose.yml
│   └── conf/
│       ├── nginx.conf
│       └── site.conf
├── webapp/
│   ├── docker-compose.yml
│   ├── .env (updated)
│   ├── cloudflared/
│   │   ├── config.yml (updated)
│   │   └── credentials.json
│   ├── nginx/
│   │   ├── nginx.conf
│   │   └── site.conf (updated)
│   └── html/ (WordPress files)
```

## Next Steps:
1. Update WordPress database URLs using one of the methods above
2. Test https://amer-alsabbagh.de
3. Verify all WordPress functionality works with the public domain
