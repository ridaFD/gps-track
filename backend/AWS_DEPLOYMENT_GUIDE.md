# AWS Deployment Guide - Cost-Effective Options

This guide covers the best and most cost-effective options for deploying your Laravel backend to AWS.

## Option Comparison

| Option | Monthly Cost* | Setup Difficulty | Best For |
|--------|--------------|------------------|----------|
| **AWS Lightsail** | $5-10 | Easy | ⭐ **Recommended - Simplest & Lowest Cost** |
| **EC2 (t3.micro)** | $7-8 | Medium | Production with more control |
| **EC2 (t3.small)** | $15-16 | Medium | Higher traffic |
| **Elastic Beanstalk** | $15-25 | Easy | Managed scaling |
| **ECS Fargate** | $20-30 | Hard | Containerized apps |

*Excluding database costs. Database adds ~$15-30/month (or free tier for 12 months)

---

## 🔄 Scalability & Upgrade Paths

### Starting with EC2? You Can Easily Upgrade!

**Yes, EC2 is highly scalable!** Here are your upgrade options:

#### 1. **Vertical Scaling (Bigger Instance)** ⬆️
**Easiest upgrade path - Just resize your instance**

| Instance Type | vCPU | RAM | Cost/Month | Best For |
|--------------|------|-----|------------|----------|
| t3.micro | 2 | 1GB | $7-8 | Starting out (Free tier) |
| t3.small | 2 | 2GB | $15-16 | Moderate traffic |
| t3.medium | 2 | 4GB | $30-32 | High traffic |
| t3.large | 2 | 8GB | $60-64 | Very high traffic |
| t3.xlarge | 4 | 16GB | $120-128 | Enterprise traffic |

**How to Upgrade:**
1. Stop your EC2 instance
2. Change instance type (Actions → Instance Settings → Change Instance Type)
3. Start instance
4. **Downtime: ~2-5 minutes**

✅ **Pros:**
- Simple, no code changes needed
- Works immediately
- Can scale up/down as needed

⚠️ **Limits:**
- Single point of failure
- Maximum instance size limits
- Short downtime during resize

#### 2. **Horizontal Scaling (Multiple Instances)** ↔️
**Best for high availability & traffic**

**Setup:**
- **Application Load Balancer** ($16/month + traffic)
- **Multiple EC2 instances** (2-3+ instances)
- **Auto Scaling Group** (adds/removes instances based on traffic)
- **Shared storage** (S3 for images, EFS for shared files)

**Cost Example:**
- 2x t3.small instances: $30/month
- Load Balancer: $16/month
- **Total: ~$46/month** (but handles 10x+ more traffic)

✅ **Pros:**
- No downtime
- Handles traffic spikes automatically
- High availability (if one fails, others continue)
- Can scale from 1 to 100+ instances

⚠️ **Cons:**
- More complex setup
- Higher cost
- Requires shared storage (S3/EFS)

#### 3. **Database Scaling** 💾

**RDS Upgrade Path:**
- db.t3.micro → db.t3.small → db.t3.medium → db.r5.large
- Read replicas (for read-heavy workloads)
- Multi-AZ deployment (high availability)

**Cost:**
- db.t3.micro: $15/month
- db.t3.small: $30/month
- db.t3.medium: $60/month

#### 4. **Migrate to Other Services** 🚀

**From EC2 to:**
- **Elastic Beanstalk**: Easy migration, managed scaling
- **ECS Fargate**: Container-based, auto-scaling
- **Lightsail**: Can't migrate from EC2, but similar ease

---

### Upgrade Path Comparison: EC2 vs Lightsail

| Feature | EC2 | Lightsail |
|---------|-----|-----------|
| **Vertical Scaling** | ✅ Easy (stop → resize → start) | ✅ Easy (change plan) |
| **Horizontal Scaling** | ✅ Full support (load balancer + auto scaling) | ⚠️ Limited (max 5 instances) |
| **Instance Types** | ✅ 100+ options | ⚠️ 5 preset plans |
| **Cost Control** | ⚠️ Pay per hour (can be more expensive) | ✅ Fixed monthly price |
| **Complexity** | ⚠️ More configuration needed | ✅ Simpler |
| **Migration Path** | ✅ Can migrate to any AWS service | ⚠️ Limited to Lightsail ecosystem |

---

### Recommended Growth Path

**Phase 1: Start Small** (0-100 users/day)
- EC2 t3.micro (Free tier) or Lightsail $5/month
- RDS db.t3.micro (Free tier)
- **Cost: $0-20/month**

**Phase 2: Growing** (100-1,000 users/day)
- EC2 t3.small or Lightsail $10/month
- RDS db.t3.small
- **Cost: $30-45/month**

**Phase 3: Scaling** (1,000-10,000 users/day)
- EC2 t3.medium + Load Balancer
- OR Elastic Beanstalk (managed)
- RDS db.t3.medium
- **Cost: $60-100/month**

**Phase 4: High Traffic** (10,000+ users/day)
- Multiple EC2 instances + Auto Scaling
- OR ECS Fargate (containers)
- RDS with read replicas
- **Cost: $150-500/month**

---

### Can You Start with Lightsail and Upgrade Later?

**Lightsail Upgrade Options:**
- ✅ Can upgrade to bigger Lightsail plans (1GB → 2GB → 4GB → 8GB)
- ⚠️ Limited to 5 instances max
- ⚠️ Can't migrate to EC2 directly (but can export)
- ✅ Can migrate database to RDS

**If you outgrow Lightsail:**
1. Export your database
2. Create new EC2 instance
3. Deploy code
4. Import database
5. Update DNS

**Recommendation:** If you expect high growth, start with EC2 for easier migration path.

---

## 🏆 **RECOMMENDED: AWS Lightsail** (Best Value)

**Why Lightsail?**
- ✅ Lowest cost ($5-10/month)
- ✅ Simplest setup (one-click Laravel)
- ✅ Includes load balancer, static IP, DNS
- ✅ Perfect for small to medium APIs
- ✅ Predictable pricing

**Monthly Cost Breakdown:**
- Lightsail Instance (1GB RAM): **$5/month**
- Lightsail Database (MySQL): **$15/month**
- **Total: ~$20/month**

### Lightsail Setup Steps

#### 1. Create Lightsail Instance
1. Go to AWS Lightsail console
2. Click "Create instance"
3. Choose:
   - **Platform**: Linux/Unix
   - **Blueprint**: **Laravel** (or Ubuntu 22.04)
   - **Instance plan**: $5/month (1GB RAM, 1 vCPU)
   - **Name**: `curtains-backend`

#### 2. Create Database
1. In Lightsail, go to "Databases"
2. Click "Create database"
3. Choose:
   - **Engine**: MySQL 8.0
   - **Plan**: $15/month (1GB RAM)
   - **Database name**: `curtains`

#### 3. Connect Database to Instance
1. In your database settings, add your Lightsail instance
2. Note the database endpoint (e.g., `curtains-db.xxxxx.us-east-1.rds.amazonaws.com`)

#### 4. Deploy Your Code

**Option A: Using Git (Recommended)**
```bash
# On your local machine
cd backend

# Push to GitHub/GitLab if not already
git init
git remote add origin YOUR_REPO_URL
git add .
git commit -m "Initial commit"
git push -u origin main

# SSH into Lightsail instance
ssh bitnami@YOUR_INSTANCE_IP

# Navigate to Laravel directory (usually /opt/bitnami/projects/laravel or /home/bitnami/htdocs)
cd /opt/bitnami/projects/laravel

# Pull your code
git clone YOUR_REPO_URL .
# OR if using Bitnami Laravel: git pull

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
nano .env  # Edit with your database credentials
```

**Option B: Using SFTP**
1. Download Lightsail SSH key
2. Use FileZilla or similar to upload files to `/opt/bitnami/projects/laravel`

#### 5. Configure Environment
```bash
# Edit .env file
nano .env
```

Update these values:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_INSTANCE_IP

DB_CONNECTION=mysql
DB_HOST=YOUR_DATABASE_ENDPOINT
DB_PORT=3306
DB_DATABASE=curtains
DB_USERNAME=bitnami
DB_PASSWORD=YOUR_DB_PASSWORD

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 6. Set Permissions
```bash
sudo chown -R bitnami:daemon /opt/bitnami/projects/laravel/storage
sudo chown -R bitnami:daemon /opt/bitnami/projects/laravel/bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 7. Configure Web Server
For Bitnami installations, the web server is usually pre-configured. If needed:
```bash
# Apache config is usually at /opt/bitnami/apache2/conf/httpd.conf
# Nginx config is usually at /opt/bitnami/nginx/conf/nginx.conf

# Restart web server
sudo /opt/bitnami/ctlscript.sh restart apache
# OR
sudo /opt/bitnami/ctlscript.sh restart nginx
```

#### 8. Set Up Static IP & Domain (Optional)
1. In Lightsail, go to "Networking"
2. Create static IP and attach to instance
3. Point your domain to the static IP

---

## Option 2: EC2 (More Control & Best Scalability)

**Monthly Cost:** $7-15/month (t3.micro or t3.small)

**Why EC2?**
- ✅ **Best scalability** - Easy vertical and horizontal scaling
- ✅ **100+ instance types** - Choose exactly what you need
- ✅ **Free tier eligible** - t3.micro free for 12 months
- ✅ **Full AWS integration** - Easy migration to other services
- ✅ **Flexible** - More control over configuration
- ⚠️ More setup required than Lightsail

### 📚 **Complete Step-by-Step Guide**

👉 **See [EC2_QUICK_START.md](EC2_QUICK_START.md) for detailed step-by-step instructions!**

The quick start guide includes:
- Complete RDS database setup
- EC2 instance creation with screenshots guidance
- Software installation
- Application deployment
- Nginx configuration
- SSL setup
- Troubleshooting

### EC2 Setup Overview (Quick Reference)

#### 1. Launch EC2 Instance
1. Go to EC2 Console → Launch Instance
2. Choose:
   - **AMI**: Ubuntu 22.04 LTS
   - **Instance type**: t3.micro (Free tier eligible) or t3.small
   - **Key pair**: Create/download SSH key
   - **Security group**: Allow HTTP (80), HTTPS (443), SSH (22)

#### 2. Install LAMP Stack
```bash
# SSH into instance
ssh -i your-key.pem ubuntu@YOUR_EC2_IP

# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1 and extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml \
    php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Nginx
sudo apt install -y nginx

# Install MySQL (or use RDS)
sudo apt install -y mysql-server
```

#### 3. Setup Database (RDS Recommended)
1. Create RDS MySQL instance:
   - **Engine**: MySQL 8.0
   - **Instance class**: db.t3.micro (Free tier eligible)
   - **Storage**: 20GB
   - **Security**: Allow your EC2 security group

#### 4. Deploy Application
```bash
# Clone your repository
cd /var/www
sudo git clone YOUR_REPO_URL curtains-backend
cd curtains-backend

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
nano .env  # Configure with RDS endpoint

# Set permissions
sudo chown -R www-data:www-data /var/www/curtains-backend
sudo chmod -R 775 storage bootstrap/cache

# Setup Laravel
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5. Configure Nginx
```bash
sudo nano /etc/nginx/sites-available/curtains
```

Add:
```nginx
server {
    listen 80;
    server_name YOUR_DOMAIN_OR_IP;
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
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/curtains /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### 6. Setup SSL (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

---

## Option 3: Elastic Beanstalk (Managed)

**Monthly Cost:** $15-25/month (includes EC2 + load balancer)

### Beanstalk Setup Steps

1. Install EB CLI: `pip install awsebcli`
2. Initialize: `eb init -p "PHP 8.1" curtains-backend`
3. Create environment: `eb create curtains-prod`
4. Deploy: `eb deploy`

**Note:** Beanstalk requires RDS separately (~$15/month)

---

## Database Options

### Option A: Lightsail Database (Recommended for Lightsail)
- **Cost**: $15/month
- **Includes**: Automated backups, high availability
- **Best for**: Lightsail instances

### Option B: RDS MySQL
- **Cost**: 
  - Free tier (12 months): $0
  - db.t3.micro: ~$15/month
  - db.t3.small: ~$30/month
- **Best for**: EC2 or Beanstalk

### Option C: EC2 MySQL (Not Recommended)
- **Cost**: Included in EC2 cost
- **Downsides**: No automated backups, manual management

---

## Storage for Images

Since you have image uploads (`storage/app/public/`), consider:

### Option A: S3 (Recommended for Production)
- **Cost**: ~$0.023/GB storage + $0.005/1000 requests
- **Setup**: Use Laravel Flysystem or `league/flysystem-aws-s3-v3`
- **Benefits**: Scalable, CDN-ready, separate from server

### Option B: Lightsail/EC2 Storage
- **Cost**: Included in instance cost
- **Limitations**: Limited by instance storage
- **OK for**: Small apps, <10GB images

---

## Cost Optimization Tips

1. **Use Free Tier**: New AWS accounts get 12 months free on EC2 t3.micro and RDS db.t3.micro
2. **Start Small**: Begin with Lightsail $5/month, scale up if needed
3. **Use S3 for Images**: Move images to S3 to reduce server storage needs
4. **Enable CloudWatch Alarms**: Monitor costs and set alerts
5. **Reserved Instances**: If committed, save 30-50% with 1-year reservations

---

## Recommended Setup for Your App

**Best Option: AWS Lightsail**
- **Instance**: $5/month (1GB RAM)
- **Database**: $15/month (MySQL)
- **Static IP**: Free
- **Total**: ~$20/month

**Why?**
- Your API is straightforward (no complex scaling needs yet)
- Lowest cost for managed service
- Simplest deployment
- Easy to upgrade later

---

## Migration Checklist

- [ ] Create AWS account
- [ ] Choose deployment option (Lightsail recommended)
- [ ] Create instance/database
- [ ] Deploy code (Git or SFTP)
- [ ] Configure `.env` with production settings
- [ ] Run migrations
- [ ] Set up storage link
- [ ] Configure web server
- [ ] Test API endpoints
- [ ] Set up SSL certificate
- [ ] Configure CORS for mobile app
- [ ] Update mobile app with new API URL
- [ ] Set up monitoring/logging
- [ ] Configure backups

---

## Post-Deployment

### Security Checklist
- [ ] `APP_DEBUG=false` in production
- [ ] Strong database passwords
- [ ] SSL certificate installed
- [ ] Firewall configured (only necessary ports)
- [ ] Regular security updates

### Performance Optimization
```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Monitoring
- Set up CloudWatch (Lightsail) or CloudWatch (EC2) for monitoring
- Configure email alerts for errors
- Set up log aggregation

---

## How to Upgrade EC2 Instance (When You Need More Power)

### Vertical Scaling (Resize Instance)

**Step-by-Step:**

1. **Stop the Instance**
   - EC2 Console → Select instance → Actions → Instance State → Stop
   - Wait for instance to fully stop (Status: "stopped")

2. **Change Instance Type**
   - Select stopped instance → Actions → Instance Settings → Change Instance Type
   - Choose new type (e.g., t3.micro → t3.small)
   - Click "Apply"

3. **Start Instance**
   - Actions → Instance State → Start
   - Wait for Status: "running"

4. **Verify**
   ```bash
   # SSH into instance
   ssh -i your-key.pem ubuntu@YOUR_IP
   
   # Check resources
   free -h  # Check RAM
   nproc    # Check CPU cores
   ```

**Downtime:** ~2-5 minutes

**When to Upgrade:**
- High CPU usage (>80% consistently)
- High memory usage (>80% consistently)
- Slow response times
- Application crashes due to memory

### Horizontal Scaling (Add Multiple Instances)

**Step-by-Step:**

1. **Create AMI (Image) of Current Instance**
   - Select instance → Actions → Image and templates → Create image
   - Name: `curtains-backend-v1`
   - Wait for AMI creation (~5-10 minutes)

2. **Create Application Load Balancer**
   - EC2 Console → Load Balancers → Create
   - Type: Application Load Balancer
   - Scheme: Internet-facing
   - Listeners: HTTP (80), HTTPS (443)
   - Target group: Create new (health checks on port 80)

3. **Create Launch Template**
   - EC2 → Launch Templates → Create
   - Use AMI created in step 1
   - Instance type: t3.small
   - Security group: Same as original
   - User data: (optional) startup script

4. **Create Auto Scaling Group**
   - EC2 → Auto Scaling Groups → Create
   - Use launch template from step 3
   - Desired: 2, Min: 1, Max: 5
   - Attach to load balancer target group
   - Health check: ELB

5. **Configure Shared Storage**
   - Move images to S3 (already recommended)
   - Use EFS for shared files if needed

6. **Update DNS**
   - Point domain to load balancer DNS name
   - Wait for DNS propagation (~5 minutes)

**Result:** Multiple instances handling traffic, auto-scaling based on load

**Cost:** ~$46/month (2x t3.small + load balancer)

---

## Next Steps

### Choose Your Starting Point:

**Option A: Start with Lightsail** (Easiest)
- Best for: Quick deployment, fixed costs, simple setup
- Upgrade path: Can scale within Lightsail, migrate to EC2 if needed later

**Option B: Start with EC2** (Best for Growth)
- Best for: If you expect high traffic, need full control, want best scalability
- Upgrade path: Easy vertical/horizontal scaling, can migrate to any AWS service

**Recommended:**
- **Start with EC2 t3.micro** (free tier) if you expect growth
- **Start with Lightsail** if you want simplicity and predictable costs

### Deployment Steps:

1. Choose your option (EC2 or Lightsail)
2. Follow the setup steps above
3. Deploy your code
4. Test your API endpoints
5. Update your mobile app with the new API URL
6. Monitor performance and scale up when needed

---

## Support Resources

- [AWS Lightsail Documentation](https://lightsail.aws.amazon.com/ls/docs/en_us/articles/)
- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [AWS Free Tier](https://aws.amazon.com/free/)

---

## Quick Start Command Reference

```bash
# On Lightsail/EC2 after deployment
cd /opt/bitnami/projects/laravel  # or /var/www/curtains-backend

# Update code
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart web server (if needed)
sudo systemctl restart nginx  # or apache2
```

---

**Estimated Total Monthly Cost: $20-30/month** (Lightsail + Database)

For lower costs, you can use EC2 t3.micro (free tier) + RDS db.t3.micro (free tier) for the first 12 months = **$0/month**!
