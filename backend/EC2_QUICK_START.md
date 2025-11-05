# EC2 Quick Start Guide - Step by Step

Complete step-by-step guide to deploy your Laravel backend on AWS EC2.

---

## Prerequisites

- AWS Account (sign up at aws.amazon.com)
- Git repository with your code (GitHub/GitLab/Bitbucket)
- Domain name (optional, but recommended)
- ~30-45 minutes for setup

---

## Step 1: Create RDS Database (Do This First)

### 1.1 Go to RDS Console
1. Log into AWS Console
2. Search for "RDS" and click it
3. Click "Create database"

### 1.2 Configure Database
- **Database creation method**: Standard create
- **Engine options**: MySQL
- **Version**: MySQL 8.0.35 (or latest)
- **Templates**: Free tier (or Production if you need more)

### 1.3 Settings
- **DB instance identifier**: `curtains-db`
- **Master username**: `admin` (or your choice)
- **Master password**: Create a strong password (save it!)
- **Confirm password**: Re-enter password

### 1.4 Instance Configuration
- **DB instance class**: `db.t3.micro` (Free tier eligible)
  - If free tier expired: Choose `db.t3.small` (~$30/month)

### 1.5 Storage
- **Storage type**: General Purpose SSD (gp3)
- **Allocated storage**: 20 GB
- **Storage autoscaling**: Enable (optional, up to 100GB)

### 1.6 Connectivity
- **VPC**: Default VPC (or create new)
- **Subnet group**: default
- **Public access**: **Yes** (for easier connection)
- **VPC security group**: Create new
  - **Security group name**: `curtains-db-sg`
- **Availability Zone**: No preference (or choose closest)

### 1.7 Database Authentication
- **Database authentication**: Password authentication

### 1.8 Additional Configuration
- **Initial database name**: `curtains`
- **Backup retention**: 7 days (free tier: 1 day)
- **Enable encryption**: Yes (recommended)

### 1.9 Create Database
- Click "Create database"
- Wait 5-10 minutes for creation

### 1.10 Note Important Information
After creation, note:
- **Endpoint**: `curtains-db.xxxxx.us-east-1.rds.amazonaws.com` (save this!)
- **Port**: `3306`
- **Username**: `admin` (or what you chose)
- **Password**: (the one you created)

### 1.11 Configure Security Group (Important!)
1. Go to RDS → Databases → Select `curtains-db`
2. Click on VPC security group link
3. Click "Edit inbound rules"
4. Add rule:
   - **Type**: MySQL/Aurora
   - **Source**: Custom (we'll add EC2 security group later)
   - For now: **Source**: My IP (to test connection)
5. Save rules

---

## Step 2: Create EC2 Instance

### 2.1 Go to EC2 Console
1. In AWS Console, search for "EC2"
2. Click "EC2"
3. Click "Launch instance"

### 2.2 Name and Tags
- **Name**: `curtains-backend`

### 2.3 Application and OS Images (AMI)
- **AMI**: Ubuntu Server 22.04 LTS (Free tier eligible)
- Region: Choose closest to you (e.g., `us-east-1`)

### 2.4 Instance Type
- **Instance type**: `t3.micro` (Free tier eligible)
  - If free tier expired: `t3.small` (~$15/month)
- Click "Review and launch" or continue to configure

### 2.5 Key Pair (Login Credentials)
- **Key pair name**: Create new key pair
  - **Name**: `curtains-key`
  - **Key pair type**: RSA
  - **Private key file format**: `.pem` (for Mac/Linux) or `.ppk` (for Windows/PuTTY)
- Click "Create key pair"
- **Download the key file** - Save it securely! You'll need it to SSH.

### 2.6 Network Settings
- **VPC**: Default VPC
- **Subnet**: Any public subnet
- **Auto-assign Public IP**: Enable
- **Firewall (security groups)**: Create new security group
  - **Security group name**: `curtains-backend-sg`
  - **Description**: Security group for curtains backend
- **Add rules**:
  1. **SSH**
     - Type: SSH
     - Port: 22
     - Source: My IP (or 0.0.0.0/0 for anywhere - less secure)
  2. **HTTP**
     - Type: HTTP
     - Port: 80
     - Source: 0.0.0.0/0 (anywhere)
  3. **HTTPS**
     - Type: HTTPS
     - Port: 443
     - Source: 0.0.0.0/0 (anywhere)

### 2.7 Configure Storage
- **Volume 1**: 20 GB gp3 (Free tier: 30 GB)
- **Delete on termination**: Keep unchecked (to preserve data)

### 2.8 Advanced Details (Optional)
- **User data** (paste this script to auto-install on first boot):
```bash
#!/bin/bash
apt update
apt upgrade -y
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml \
    php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath nginx git unzip
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### 2.9 Launch Instance
1. Click "Launch instance"
2. Wait for instance to be "Running" (Status checks: 2/2 checks passed)
3. Note the **Public IPv4 address** (e.g., `54.123.45.67`)

### 2.10 Allocate Elastic IP (Optional but Recommended)
1. EC2 → Network & Security → Elastic IPs
2. Click "Allocate Elastic IP address"
3. Click "Allocate"
4. Select the Elastic IP → Actions → Associate Elastic IP address
5. Choose your instance → Associate

**Why?** Your IP won't change when you restart the instance.

---

## Step 3: Connect to EC2 Instance

### 3.1 Set Key Permissions (Mac/Linux)
```bash
chmod 400 curtains-key.pem
```

### 3.2 SSH into Instance
```bash
ssh -i curtains-key.pem ubuntu@YOUR_EC2_IP
```

Replace:
- `curtains-key.pem` with your key file path
- `YOUR_EC2_IP` with your EC2 public IP

**First time?** You'll see a warning - type `yes` to continue.

### 3.3 Update System
```bash
sudo apt update && sudo apt upgrade -y
```

---

## Step 4: Install Required Software

### 4.1 Install PHP 8.1 and Extensions
```bash
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml \
    php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath
```

### 4.2 Verify PHP Installation
```bash
php -v
# Should show PHP 8.1.x
```

### 4.3 Install Composer
```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### 4.4 Install Nginx
```bash
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

### 4.5 Install Git (if not already installed)
```bash
sudo apt install -y git
```

---

## Step 5: Configure RDS Security Group

### 5.1 Get EC2 Security Group ID
1. In EC2 Console → Instances
2. Select your instance → Security tab
3. Note the Security group ID (e.g., `sg-0123456789abcdef0`)

### 5.2 Update RDS Security Group
1. Go to RDS → Databases → Select `curtains-db`
2. Click on VPC security group link
3. Click "Edit inbound rules"
4. Delete the "My IP" rule
5. Add rule:
   - **Type**: MySQL/Aurora
   - **Port**: 3306
   - **Source**: Custom
   - **Custom**: Select your EC2 security group (`curtains-backend-sg`)
6. Save rules

**Now your EC2 can connect to RDS!**

---

## Step 6: Deploy Your Application

### 6.1 Clone Your Repository
```bash
cd /var/www
sudo git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git curtains-backend
cd curtains-backend
```

**Or if using SSH:**
```bash
sudo git clone git@github.com:YOUR_USERNAME/YOUR_REPO.git curtains-backend
```

**If your repo is private:**
1. Generate SSH key on EC2:
```bash
ssh-keygen -t rsa -b 4096 -C "ec2@curtains"
cat ~/.ssh/id_rsa.pub
# Copy the output and add it to GitHub/GitLab as a deploy key
```

### 6.2 Install Composer Dependencies
```bash
cd /var/www/curtains-backend
sudo composer install --no-dev --optimize-autoloader
```

### 6.3 Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/curtains-backend
sudo chmod -R 775 /var/www/curtains-backend/storage
sudo chmod -R 775 /var/www/curtains-backend/bootstrap/cache
```

### 6.4 Configure Environment File
```bash
sudo cp .env.example .env
sudo nano .env
```

**Update these values:**
```env
APP_NAME="Vicci Home Curtains"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://YOUR_EC2_IP
# Or if you have a domain:
# APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=YOUR_RDS_ENDPOINT
DB_PORT=3306
DB_DATABASE=curtains
DB_USERNAME=admin
DB_PASSWORD=YOUR_RDS_PASSWORD

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Add these if you have them:
# REDIS_HOST=127.0.0.1
# REDIS_PASSWORD=null
# REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Replace:**
- `YOUR_EC2_IP` with your EC2 public IP or domain
- `YOUR_RDS_ENDPOINT` with your RDS endpoint (from Step 1.10)
- `YOUR_RDS_PASSWORD` with your RDS password

**Save and exit:** `Ctrl+X`, then `Y`, then `Enter`

### 6.5 Generate Application Key
```bash
sudo php artisan key:generate
```

### 6.6 Run Migrations
```bash
sudo php artisan migrate --force
```

### 6.7 Create Storage Link
```bash
sudo php artisan storage:link
```

### 6.8 Optimize Laravel
```bash
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
```

---

## Step 7: Configure Nginx

### 7.1 Create Nginx Configuration
```bash
sudo nano /etc/nginx/sites-available/curtains
```

**Paste this configuration:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name YOUR_EC2_IP your-domain.com;
    root /var/www/curtains-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Replace:**
- `YOUR_EC2_IP` with your EC2 IP or Elastic IP
- `your-domain.com` with your domain (or remove if you don't have one)

**Save and exit:** `Ctrl+X`, then `Y`, then `Enter`

### 7.2 Enable Site
```bash
sudo ln -s /etc/nginx/sites-available/curtains /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default  # Remove default site
```

### 7.3 Test Nginx Configuration
```bash
sudo nginx -t
```

Should show: `nginx: configuration file /etc/nginx/nginx.conf test is successful`

### 7.4 Restart Nginx
```bash
sudo systemctl restart nginx
sudo systemctl status nginx
```

---

## Step 8: Test Your Application

### 8.1 Test in Browser
Open: `http://YOUR_EC2_IP`

You should see your Laravel application or API.

### 8.2 Test API Endpoint
```bash
curl http://YOUR_EC2_IP/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"password123","password_confirmation":"password123"}'
```

### 8.3 Check Logs (if errors)
```bash
sudo tail -f /var/www/curtains-backend/storage/logs/laravel.log
```

---

## Step 9: Setup SSL Certificate (Optional but Recommended)

### 9.1 Install Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 9.2 Get SSL Certificate
```bash
sudo certbot --nginx -d your-domain.com
```

**If you don't have a domain:**
- You can use the EC2 IP, but SSL won't work with IP only
- Consider getting a free domain from Freenom or use AWS Route 53

### 9.3 Auto-Renewal
Certbot sets up auto-renewal automatically. Test it:
```bash
sudo certbot renew --dry-run
```

---

## Step 10: Update Mobile App Configuration

Update your mobile app's API base URL:

**File:** `mobile/lib/services/api_service.dart`

```dart
// Change from:
// static const String baseUrl = 'http://localhost:8000';

// To:
static const String baseUrl = 'http://YOUR_EC2_IP';
// Or if you have SSL:
static const String baseUrl = 'https://your-domain.com';
```

---

## Step 11: Configure Automatic Deployments (Optional)

### 11.1 Create Deployment Script
```bash
sudo nano /home/ubuntu/deploy.sh
```

**Paste:**
```bash
#!/bin/bash
cd /var/www/curtains-backend
sudo git pull origin main
sudo composer install --no-dev --optimize-autoloader
sudo php artisan migrate --force
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo chown -R www-data:www-data /var/www/curtains-backend
sudo systemctl reload nginx
echo "Deployment completed!"
```

**Make executable:**
```bash
chmod +x /home/ubuntu/deploy.sh
```

### 11.2 Deploy
```bash
sudo /home/ubuntu/deploy.sh
```

---

## Troubleshooting

### Can't connect to RDS?
1. Check RDS security group allows your EC2 security group
2. Verify RDS endpoint is correct
3. Test connection:
```bash
mysql -h YOUR_RDS_ENDPOINT -u admin -p
```

### 502 Bad Gateway?
1. Check PHP-FPM is running:
```bash
sudo systemctl status php8.1-fpm
sudo systemctl restart php8.1-fpm
```

2. Check Nginx error logs:
```bash
sudo tail -f /var/log/nginx/error.log
```

### Permission Denied?
```bash
sudo chown -R www-data:www-data /var/www/curtains-backend
sudo chmod -R 775 /var/www/curtains-backend/storage
sudo chmod -R 775 /var/www/curtains-backend/bootstrap/cache
```

### Can't access application?
1. Check security group allows HTTP (port 80)
2. Check Nginx is running:
```bash
sudo systemctl status nginx
```

3. Check firewall:
```bash
sudo ufw status
sudo ufw allow 80
sudo ufw allow 443
```

---

## Cost Summary

**Monthly Costs:**
- EC2 t3.micro: **$0** (Free tier) or **$7-8/month**
- RDS db.t3.micro: **$0** (Free tier) or **$15/month**
- Elastic IP: **Free** (if attached to running instance)
- Data transfer: **~$0-5/month** (first 1GB free)

**Total: $0-28/month** (depending on free tier eligibility)

---

## Next Steps

1. ✅ Set up monitoring (CloudWatch)
2. ✅ Configure backups
3. ✅ Set up staging environment
4. ✅ Enable logging
5. ✅ Scale up when needed (see main guide)

---

## Quick Commands Reference

```bash
# SSH into instance
ssh -i curtains-key.pem ubuntu@YOUR_EC2_IP

# Check application status
sudo systemctl status nginx
sudo systemctl status php8.1-fpm

# View logs
sudo tail -f /var/www/curtains-backend/storage/logs/laravel.log
sudo tail -f /var/log/nginx/error.log

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm

# Deploy updates
cd /var/www/curtains-backend
sudo git pull
sudo composer install --no-dev --optimize-autoloader
sudo php artisan migrate --force
sudo php artisan config:cache
sudo php artisan route:cache
sudo systemctl reload nginx
```

---

## Security Checklist

- [ ] Change default SSH port (optional)
- [ ] Disable root login
- [ ] Set up firewall (UFW)
- [ ] Enable SSL certificate
- [ ] Use strong database passwords
- [ ] Keep system updated: `sudo apt update && sudo apt upgrade`
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure CORS properly
- [ ] Set up CloudWatch alarms

---

**Congratulations! Your Laravel backend is now running on AWS EC2! 🎉**

Need help? Check the main `AWS_DEPLOYMENT_GUIDE.md` for more details.
