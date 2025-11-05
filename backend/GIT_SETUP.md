# Git Setup Guide

Your backend is now initialized with git! Here's how to push it to a remote repository.

## Current Status

✅ Git repository initialized  
✅ Initial commit created  
✅ All files committed (212 files, 34,754 lines)  
✅ `.gitignore` configured to exclude sensitive files

## Next Steps: Add Remote Repository

### Option 1: GitHub (Most Popular)

#### 1. Create Repository on GitHub
1. Go to [github.com](https://github.com)
2. Click "+" → "New repository"
3. Name: `curtains-backend` (or your choice)
4. Description: "Laravel backend API for Curtains mobile app"
5. Visibility: Private (recommended) or Public
6. **Don't** initialize with README, .gitignore, or license (we already have these)
7. Click "Create repository"

#### 2. Add Remote and Push
```bash
cd /Users/ridafakherlden/www/curtains/backend

# Add GitHub remote (replace with your username and repo name)
git remote add origin https://github.com/YOUR_USERNAME/curtains-backend.git

# Or if using SSH:
# git remote add origin git@github.com:YOUR_USERNAME/curtains-backend.git

# Push to GitHub
git branch -M main
git push -u origin main
```

**If prompted for credentials:**
- Use GitHub Personal Access Token (not password)
- Or set up SSH keys for easier authentication

---

### Option 2: GitLab

#### 1. Create Repository on GitLab
1. Go to [gitlab.com](https://gitlab.com)
2. Click "New project" → "Create blank project"
3. Project name: `curtains-backend`
4. Visibility: Private or Public
5. Click "Create project"

#### 2. Add Remote and Push
```bash
cd /Users/ridafakherlden/www/curtains/backend

# Add GitLab remote
git remote add origin https://gitlab.com/YOUR_USERNAME/curtains-backend.git

# Push to GitLab
git branch -M main
git push -u origin main
```

---

### Option 3: Bitbucket

#### 1. Create Repository on Bitbucket
1. Go to [bitbucket.org](https://bitbucket.org)
2. Click "Create" → "Repository"
3. Repository name: `curtains-backend`
4. Access level: Private or Public
5. Click "Create repository"

#### 2. Add Remote and Push
```bash
cd /Users/ridafakherlden/www/curtains/backend

# Add Bitbucket remote
git remote add origin https://bitbucket.org/YOUR_USERNAME/curtains-backend.git

# Push to Bitbucket
git branch -M main
git push -u origin main
```

---

## Verify Setup

```bash
# Check remote
git remote -v

# Check status
git status

# View commit history
git log --oneline
```

---

## Common Git Commands

### Daily Workflow
```bash
# Check status
git status

# Add changes
git add .

# Commit changes
git commit -m "Description of changes"

# Push to remote
git push origin main

# Pull latest changes
git pull origin main
```

### Viewing Changes
```bash
# See what changed
git diff

# See commit history
git log --oneline --graph

# See what files changed in last commit
git show --name-only
```

### Branching (Optional)
```bash
# Create new branch
git checkout -b feature/new-feature

# Switch branches
git checkout main

# Merge branch
git merge feature/new-feature

# Delete branch
git branch -d feature/new-feature
```

---

## Important Notes

### What's Included in Git
- ✅ All source code
- ✅ Configuration files
- ✅ Documentation
- ✅ Migrations
- ✅ Routes, controllers, models

### What's NOT Included (via .gitignore)
- ❌ `.env` file (sensitive credentials)
- ❌ `vendor/` folder (Composer dependencies)
- ❌ `node_modules/` (npm dependencies)
- ❌ Uploaded files in `storage/app/public/`
- ❌ Log files
- ❌ Cache files

### For Deployment
When deploying to EC2, you'll need to:
1. Clone the repository
2. Copy `.env.example` to `.env`
3. Configure `.env` with production values
4. Run `composer install` to install dependencies
5. Run migrations

---

## Troubleshooting

### "Permission denied" when pushing
- Use Personal Access Token instead of password
- Or set up SSH keys

### "Remote origin already exists"
```bash
# Remove existing remote
git remote remove origin

# Add new remote
git remote add origin YOUR_REPO_URL
```

### Undo last commit (before pushing)
```bash
git reset --soft HEAD~1
```

### Undo changes to a file
```bash
git checkout -- filename
```

---

## Security Best Practices

1. **Never commit `.env` file** - It contains sensitive data
2. **Use `.env.example`** - Template file with dummy values
3. **Review changes before committing** - `git status` and `git diff`
4. **Use meaningful commit messages** - Describe what changed
5. **Keep repository private** - Especially for production code

---

## Next Steps

After pushing to remote:
1. ✅ Update EC2 deployment guide with your repository URL
2. ✅ Test cloning on EC2 instance
3. ✅ Set up automatic deployments (optional)
4. ✅ Configure CI/CD (optional)

---

**Need help?** Check the main deployment guides:
- [EC2_QUICK_START.md](EC2_QUICK_START.md)
- [AWS_DEPLOYMENT_GUIDE.md](AWS_DEPLOYMENT_GUIDE.md)
