# EC2 Deployment Checklist

Use this checklist to track your deployment progress.

## Pre-Deployment

- [ ] AWS account created
- [ ] Git repository ready (code pushed)
- [ ] Domain name purchased (optional)
- [ ] SSH key pair ready (will create during EC2 setup)

## Database Setup (RDS)

- [ ] RDS MySQL instance created
- [ ] Database endpoint saved
- [ ] Database username and password saved
- [ ] Database security group configured
- [ ] Database allows EC2 security group access

## EC2 Instance Setup

- [ ] EC2 instance launched
- [ ] Instance type selected (t3.micro or t3.small)
- [ ] Key pair downloaded and saved securely
- [ ] Security group created with HTTP, HTTPS, SSH rules
- [ ] Elastic IP allocated (optional but recommended)
- [ ] Instance status: Running (2/2 checks passed)

## Server Configuration

- [ ] Connected to EC2 via SSH
- [ ] System updated (`sudo apt update && sudo apt upgrade`)
- [ ] PHP 8.1 installed with all required extensions
- [ ] Composer installed
- [ ] Nginx installed and running
- [ ] Git installed

## Application Deployment

- [ ] Code cloned from repository
- [ ] Composer dependencies installed
- [ ] `.env` file created and configured
- [ ] Database credentials set in `.env`
- [ ] `APP_URL` set correctly
- [ ] Application key generated (`php artisan key:generate`)
- [ ] Database migrations run (`php artisan migrate`)
- [ ] Storage link created (`php artisan storage:link`)
- [ ] Permissions set correctly (storage, cache)
- [ ] Laravel optimized (config, route, view cache)

## Web Server Configuration

- [ ] Nginx configuration file created
- [ ] Nginx configuration tested (`nginx -t`)
- [ ] Nginx restarted
- [ ] Application accessible via HTTP

## Security & SSL

- [ ] SSL certificate installed (Certbot)
- [ ] HTTPS working
- [ ] `APP_DEBUG=false` in production
- [ ] Strong database passwords set
- [ ] Firewall configured (UFW)
- [ ] Security group rules reviewed

## Testing

- [ ] Application loads in browser
- [ ] API endpoints responding
- [ ] Database connection working
- [ ] Authentication endpoints tested
- [ ] Protected endpoints tested with token
- [ ] Image upload working (if applicable)
- [ ] Error logs checked

## Mobile App Integration

- [ ] Mobile app API base URL updated
- [ ] CORS configured correctly
- [ ] Mobile app tested with new API URL
- [ ] Authentication flow working from mobile

## Post-Deployment

- [ ] Monitoring set up (CloudWatch)
- [ ] Backups configured
- [ ] Deployment script created
- [ ] Documentation updated
- [ ] Team notified of new API URL

## Optional Enhancements

- [ ] CI/CD pipeline configured
- [ ] Staging environment set up
- [ ] CloudWatch alarms configured
- [ ] S3 bucket for image storage
- [ ] CDN configured
- [ ] Auto-scaling configured (if needed)

## Cost Monitoring

- [ ] CloudWatch billing alerts set up
- [ ] Monthly cost estimate reviewed
- [ ] Free tier usage tracked

---

## Quick Reference

**EC2 Instance IP:** `___________________`  
**RDS Endpoint:** `___________________`  
**Domain:** `___________________`  
**SSH Key:** `___________________`  

**Notes:**
```
_______________________________________________________
_______________________________________________________
_______________________________________________________
```

---

**Status:** ☐ In Progress  ☐ Complete  ☐ Blocked

**Last Updated:** ___________________
