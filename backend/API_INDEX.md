# Curtains API - Quick Start Index

Welcome to the Curtains API! This index will help you navigate the API documentation.

## 📚 Documentation Files

### 1. [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
**Complete API Reference**
- All 34 endpoints documented
- Request/response examples
- Authentication flow
- Error responses
- Status codes

**Start here if:** You want to explore all available endpoints

### 2. [API_SETUP.md](API_SETUP.md)
**Setup and Testing Guide**
- Installation steps
- Environment configuration
- Testing with cURL and Postman
- Troubleshooting
- Production deployment

**Start here if:** You need to set up and test the API

### 3. [FLUTTER_API_CLIENT.md](FLUTTER_API_CLIENT.md)
**Flutter Integration Guide**
- Complete Dart API service class
- Usage examples
- Provider pattern implementation
- Best practices
- Error handling

**Start here if:** You're building the Flutter mobile app

### 4. [API_SUMMARY.md](API_SUMMARY.md)
**Implementation Overview**
- What was built
- File structure
- Features list
- Security features
- Testing checklist

**Start here if:** You want a high-level overview

### 5. [README.md](README.md)
**Project Main Readme**
- Quick setup instructions
- Endpoint overview
- Link to all documentation

**Start here if:** You're new to the project

## 🚀 Quick Start

### For Developers Setting Up the API

1. Read [API_SETUP.md](API_SETUP.md)
2. Configure your environment
3. Run migrations
4. Test with cURL examples

### For Flutter Developers

1. Read [FLUTTER_API_CLIENT.md](FLUTTER_API_CLIENT.md)
2. Copy the ApiService class
3. Review usage examples
4. Start integrating!

### For API Consumers

1. Read [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
2. Review authentication section
3. Explore endpoints
4. Check request/response examples

## 📍 Common Tasks

### I want to...

**...authenticate a user**
→ [API_DOCUMENTATION.md - Authentication](API_DOCUMENTATION.md#authentication)

**...get a list of orders**
→ [API_DOCUMENTATION.md - Orders](API_DOCUMENTATION.md#orders)

**...create a new order**
→ [API_DOCUMENTATION.md - Create Order](API_DOCUMENTATION.md#create-order)

**...upload an image**
→ [API_DOCUMENTATION.md - Upload Image](API_DOCUMENTATION.md#upload-line-image)

**...filter results**
→ [API_DOCUMENTATION.md - Filtering](API_DOCUMENTATION.md#filtering--querying)

**...understand errors**
→ [API_DOCUMENTATION.md - Error Responses](API_DOCUMENTATION.md#error-responses)

**...test the API**
→ [API_SETUP.md - Testing](API_SETUP.md#testing-the-api)

**...integrate with Flutter**
→ [FLUTTER_API_CLIENT.md - Usage](FLUTTER_API_CLIENT.md#3-usage-example)

## 🔑 Key Concepts

### Authentication
- Token-based with Laravel Sanctum
- Bearer token in Authorization header
- No token expiration by default

### Endpoints
- Base URL: `http://your-domain.com/api`
- Public: Register, Login
- Protected: Everything else

### Resources
- Orders: Customer orders
- Blinds: Blind products/inventory
- Order Lines: Line items in orders
- Order Blinds: Blinds in orders

### Response Format
- JSON
- Pagination for lists
- Consistent structure
- Error details included

## 📊 API Statistics

- **Total Endpoints**: 34
- **Controllers**: 5
- **Resources**: 5
- **Models**: 5
- **Authentication**: Sanctum
- **Rate Limit**: 60 req/min

## 🛠️ Technology Stack

- **Framework**: Laravel 10.x
- **Auth**: Laravel Sanctum
- **Database**: MySQL/PostgreSQL
- **PHP**: >= 8.1

## 📞 Support

- Check logs: `storage/logs/laravel.log`
- Review error messages
- Check documentation
- Contact development team

## 🔄 Workflow

### Typical Integration Flow

1. **Setup** → Read API_SETUP.md, configure environment
2. **Authenticate** → Register/login to get token
3. **Explore** → Use API_DOCUMENTATION.md to find endpoints
4. **Implement** → Use FLUTTER_API_CLIENT.md for Flutter code
5. **Test** → Use cURL or Postman to verify
6. **Deploy** → Follow production setup in API_SETUP.md

## 📝 Checklist

Before deploying your Flutter app:

- [ ] API base URL configured
- [ ] Authentication implemented
- [ ] All endpoints tested
- [ ] Error handling added
- [ ] Loading states implemented
- [ ] Image uploads working
- [ ] Offline handling considered
- [ ] Production environment configured
- [ ] SSL/HTTPS enabled
- [ ] Monitoring set up

## 🎯 Next Steps

1. ✅ Read the documentation
2. ✅ Set up development environment
3. ✅ Test API endpoints
4. ✅ Implement Flutter client
5. ✅ Add error handling
6. ✅ Test on devices
7. ✅ Deploy to production

---

**Happy coding! 🚀**

For detailed information, refer to the specific documentation files listed above.

