# 📱 Modern Social Blogging Platform

A feature-rich, Instagram-inspired social blogging platform built with Laravel 11, featuring real-time interactions, stories, and a beautiful dark/light theme interface.

---

## 🌟 **Project Overview**

This is a full-stack web application that combines the best of blogging and social media. Users can create blog posts, share temporary stories (like Instagram), interact with content through reactions and comments, follow other users, and discover trending content.

**Live URL:** `http://127.0.0.1:8000` (Local Development)

**Tech Stack:**
- **Backend:** Laravel 11 (PHP)
- **Frontend:** Blade Templates, Tailwind CSS
- **Database:** MySQL
- **Icons:** Google Material Symbols
- **Server:** XAMPP (Apache + MySQL)

---

## 🎯 **Key Features**

### 1. **User Authentication & Authorization**
- ✅ User Registration with detailed profile information
- ✅ Login/Logout functionality
- ✅ Password validation (minimum 6 characters)
- ✅ Automatic username generation from email
- ✅ Role-based access control (User/Admin)
- ✅ Session management with CSRF protection

**User Registration Fields:**
- Name, Email, Password
- Date of Birth, Phone Number
- Address Details (Village, Post Office, Police Station, District)

### 2. **Blog Post Management**
Create and manage rich blog content with images and videos.

**Features:**
- ✅ Create posts with title, description, and media
- ✅ Upload images and videos
- ✅ Draft and Published status
- ✅ Edit and delete your own posts
- ✅ Rich text descriptions
- ✅ Post tagging system
- ✅ Public and private posts

**Post Display:**
- Masonry grid layout
- Full-screen image modal viewer
- Video player support
- Author information with avatar
- Timestamp (time ago format)
- Reaction counts and comment counts

### 3. **Instagram-Style Stories**
24-hour temporary content sharing with modern UI.

**Story Features:**
- ✅ Upload image or video stories
- ✅ Add captions to stories
- ✅ Auto-delete after 24 hours
- ✅ Stories from followed users only
- ✅ Full-screen story viewer
- ✅ Progress bars for multiple stories
- ✅ Tap left/right to navigate
- ✅ Keyboard navigation (Arrow keys, Escape)
- ✅ React to stories with heart icon
- ✅ Delete your own stories
- ✅ View reaction counts
- ✅ Gradient ring indicators
- ✅ Story count badges

**Story Viewer Controls:**
- Tap right side: Next story
- Tap left side: Previous story
- Heart button: Like story
- X button: Close viewer
- Delete button: Remove your story
- Auto-progress: 5 seconds per story

### 4. **Social Interactions**

**Reactions System:**
- ✅ Like/Unlike posts with heart icon
- ✅ React to stories
- ✅ Real-time reaction count updates
- ✅ Visual feedback (filled heart animation)
- ✅ Multiple reaction types support

**Comments System:**
- ✅ Add comments on posts
- ✅ Nested replies (parent-child structure)
- ✅ Edit your own comments
- ✅ Delete your own comments
- ✅ Comment count display
- ✅ Real-time comment updates

**Bookmarks:**
- ✅ Save posts for later
- ✅ Dedicated bookmarks page
- ✅ Toggle bookmark with one click
- ✅ Visual indicator (filled bookmark icon)

### 5. **Follow System**
Build your social network within the platform.

**Features:**
- ✅ Follow/Unfollow users
- ✅ Followers list page
- ✅ Following list page
- ✅ Follower/Following counts on profiles
- ✅ See stories from followed users
- ✅ Feed based on following
- ✅ Follow button on profiles
- ✅ Mutual follow indicators

### 6. **User Profiles**
Personalized profile pages with customization options.

**Profile Features:**
- ✅ Profile picture (avatar)
- ✅ Cover photo
- ✅ Bio (500 characters max)
- ✅ Username (unique, URL-friendly)
- ✅ Post count statistics
- ✅ Followers/Following statistics
- ✅ User's published posts grid
- ✅ Edit profile page
- ✅ Change password functionality
- ✅ Address information display

**Profile URL:** `/users/{username}`

### 7. **News Feed & Dashboard**
Personalized content feed with multiple sections.

**Dashboard Features:**
- ✅ Stories carousel at top
- ✅ "Add Story" button
- ✅ Feed from followed users
- ✅ Trending posts sidebar
- ✅ Your recent posts section
- ✅ Statistics cards (posts, followers, following)
- ✅ Infinite scroll support
- ✅ Pull to refresh

**Feed Sorting:**
- Latest posts first
- Posts from followed users
- User's own posts included

### 8. **Explore & Discovery**
Discover new content and users.

**Explore Features:**
- ✅ Trending posts section
- ✅ Popular tags cloud
- ✅ Popular users/creators
- ✅ Filter by tags
- ✅ Tag-based post discovery
- ✅ Posts count per tag
- ✅ Trending indicators

**Available Pages:**
- `/explore` - Main explore page
- `/explore/trending` - Trending posts
- `/explore/popular-users` - Popular creators
- `/tags/{tag}` - Posts by specific tag

### 9. **Search Functionality**
Global search across the platform.

**Search Features:**
- ✅ Search posts by title/description
- ✅ Search users by name/username/email
- ✅ Autocomplete suggestions
- ✅ Real-time search results
- ✅ Search from navigation bar
- ✅ Instant results display

### 10. **Notifications System**
Stay updated with platform activities.

**Notification Types:**
- ✅ New follower notifications
- ✅ Post reaction notifications
- ✅ Comment notifications
- ✅ Reply notifications
- ✅ Mention notifications (future)

**Notification Features:**
- ✅ Real-time unread count badge
- ✅ Notification dropdown menu
- ✅ Mark as read functionality
- ✅ Mark all as read option
- ✅ Delete notifications
- ✅ Notification timestamps
- ✅ Auto-refresh every 30 seconds

### 11. **Admin Dashboard**
Comprehensive admin panel for content management.

**Admin Features:**
- ✅ Dashboard with statistics
- ✅ Total users count
- ✅ Total posts count
- ✅ Active stories count
- ✅ User management (ban/unban)
- ✅ Make users admin
- ✅ Post moderation (delete)
- ✅ Comment moderation (delete)
- ✅ User activity monitoring

**Admin URL:** `/admin/dashboard`

### 12. **Media Management**
Advanced media handling capabilities.

**Features:**
- ✅ Image upload and storage
- ✅ Video upload support
- ✅ Automatic file validation
- ✅ Image optimization
- ✅ Storage symlink configuration
- ✅ Media file tracking
- ✅ Automatic cleanup on deletion
- ✅ Multiple file format support

**Supported Formats:**
- Images: JPG, JPEG, PNG, GIF
- Videos: MP4, MOV

### 13. **UI/UX Features**
Modern, responsive interface with attention to detail.

**Design Features:**
- ✅ Dark/Light theme toggle
- ✅ Gradient accents (purple/pink)
- ✅ Material Symbols icons
- ✅ Responsive design (mobile-first)
- ✅ Smooth animations and transitions
- ✅ Loading states
- ✅ Toast notifications
- ✅ Modal dialogs
- ✅ Full-screen viewers
- ✅ Hover effects
- ✅ Skeleton loaders (future)

**Color Scheme:**
- Primary: Purple (#9333EA)
- Secondary: Pink (#EC4899)
- Dark Mode: Gray-900 background
- Light Mode: White background

### 14. **Performance Optimizations**
Built for speed and efficiency.

**Optimizations:**
- ✅ Database query optimization
- ✅ Eager loading relationships
- ✅ Story caching (30 seconds)
- ✅ Indexed database columns
- ✅ Pagination on large datasets
- ✅ Lazy loading images
- ✅ Optimized queries with counts
- ✅ Route caching support

### 15. **Security Features**
Enterprise-level security implementation.

**Security Measures:**
- ✅ CSRF protection on all forms
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade escaping)
- ✅ Password hashing (bcrypt)
- ✅ Authentication middleware
- ✅ Authorization policies
- ✅ Route protection
- ✅ Session security
- ✅ File upload validation
- ✅ Role-based access control

---

## 📖 **How to Use the Platform**

### **For New Users:**

#### Step 1: Registration
1. Visit `http://127.0.0.1:8000/register`
2. Fill in your details:
   - Name, Email, Password
   - Date of Birth, Phone Number
   - Address (optional)
3. Click "Register"
4. You'll be automatically logged in

#### Step 2: Set Up Your Profile
1. Click your avatar in the top-right corner
2. Select "Edit Profile"
3. Upload profile picture and cover photo
4. Add a bio (tell others about yourself)
5. Set a custom username
6. Click "Update Profile"

#### Step 3: Create Your First Post
1. Click "Blog Posts" in navigation
2. Click "+ Create Post"
3. Add a title and description
4. Upload an image or video
5. Add tags (comma-separated)
6. Choose "Published" status
7. Click "Create Post"

#### Step 4: Share a Story
1. Click the "Stories" icon (dashed circle with +)
2. Click "Create Story"
3. Upload an image or video
4. Add a caption (optional)
5. Click "Post Story"
6. Your story will appear in the stories carousel

#### Step 5: Discover Content
1. **Home Feed:** See posts from users you follow
2. **Explore:** Discover trending content and popular users
3. **Search:** Find specific posts or users
4. **Tags:** Click tags to see related posts

#### Step 6: Interact with Content
- **Like:** Click the heart icon on any post
- **Comment:** Click comment icon and type your thoughts
- **Bookmark:** Click bookmark icon to save for later
- **Share:** Share posts on your profile (future feature)

#### Step 7: Build Your Network
1. Visit user profiles by clicking their avatars
2. Click "Follow" button
3. View their posts and stories
4. See followers/following lists

---

## 🚀 **User Workflows**

### **Workflow 1: Daily Social Media Use**
```
1. Login → 2. View Stories → 3. Check Feed → 4. React to Posts → 5. Post Story → 6. Logout
```

### **Workflow 2: Content Creator**
```
1. Login → 2. Create Blog Post → 3. Add Tags → 4. Publish → 5. Check Reactions → 6. Reply to Comments
```

### **Workflow 3: Content Discovery**
```
1. Visit Explore → 2. Check Trending → 3. Click Popular Tags → 4. Follow New Users → 5. Save Bookmarks
```

### **Workflow 4: Story Sharing**
```
1. Click Stories Icon → 2. Create Story → 3. Upload Media → 4. Add Caption → 5. Post → 6. View Reactions
```

### **Workflow 5: Admin Management**
```
1. Login as Admin → 2. Access Dashboard → 3. Monitor Users → 4. Moderate Content → 5. Ban/Warn Users
```

---

## 🎨 **Page Structure**

### **Public Pages:**
- `/` - Welcome/Landing page
- `/login` - Login form
- `/register` - Registration form
- `/blogs` - Public blog posts listing

### **Authenticated Pages:**
- `/home` - User home feed
- `/dashboard` - Main dashboard with stories
- `/explore` - Explore trending content
- `/search` - Global search
- `/stories` - Stories gallery
- `/stories/create` - Create new story
- `/stories/{userId}` - View user stories
- `/bookmarks` - Saved posts
- `/notifications` - Notification center

### **User Pages:**
- `/users/{username}` - Public profile
- `/users/{username}/followers` - Followers list
- `/users/{username}/following` - Following list
- `/profile/edit` - Edit own profile

### **Post Pages:**
- `/posts/{post}` - Single post view
- `/dashboard/create` - Create new post
- `/posts/{post}/edit` - Edit post
- `/blogs/{post}` - Public blog post view
- `/tags/{tag}` - Posts by tag

### **Admin Pages:**
- `/admin/dashboard` - Admin overview
- `/admin/users` - User management
- `/admin/posts` - Post management
- `/admin/comments` - Comment management

---

## 🔧 **Technical Architecture**

### **Backend Structure:**
```
app/
├── Http/Controllers/
│   ├── Auth/
│   │   ├── LoginController.php
│   │   └── RegisterController.php
│   ├── Admin/
│   │   └── AdminController.php
│   ├── PostController.php
│   ├── StoryController.php
│   ├── CommentController.php
│   ├── ReactionController.php
│   ├── FollowController.php
│   ├── BookmarkController.php
│   ├── NotificationController.php
│   ├── ProfileController.php
│   ├── SearchController.php
│   └── ExploreController.php
├── Models/
│   ├── User.php
│   ├── Post.php
│   ├── Story.php
│   ├── Comment.php
│   ├── Reaction.php
│   ├── Bookmark.php
│   ├── Notification.php
│   ├── Tag.php
│   └── MediaFile.php
└── Providers/
    └── AppServiceProvider.php
```

### **Database Schema:**

**Users Table:**
- id, name, username, email, password
- dob, phone, village, post, police_station, district
- bio, avatar, cover_photo, role
- timestamps

**Posts Table:**
- id, user_id, title, description
- image_path, video_path, status
- timestamps

**Stories Table:**
- id, user_id, media_path, media_type
- caption, expires_at
- timestamps

**Comments Table:**
- id, user_id, commentable_id, commentable_type
- parent_id, content
- timestamps

**Reactions Table:**
- id, user_id, reactable_id, reactable_type
- type (like, love, laugh, etc.)
- timestamps

**Follows Table:**
- id, follower_id, following_id
- timestamps

**Bookmarks Table:**
- id, user_id, post_id
- timestamps

**Tags Table:**
- id, name, slug
- timestamps

**Notifications Table:**
- id, user_id, type, data
- read_at
- timestamps

### **Key Relationships:**
- User → Posts (One to Many)
- User → Stories (One to Many)
- User → Comments (One to Many)
- Post → Comments (Polymorphic)
- Post → Reactions (Polymorphic)
- Post → Tags (Many to Many)
- User → Followers (Many to Many)
- User → Bookmarks (Many to Many)

---

## 📱 **Mobile Responsiveness**

The platform is fully responsive and works on:
- ✅ Desktop (1920px+)
- ✅ Laptop (1366px - 1920px)
- ✅ Tablet (768px - 1365px)
- ✅ Mobile (320px - 767px)

**Mobile Features:**
- Hamburger menu
- Touch-friendly buttons
- Swipe gestures for stories
- Optimized image sizes
- Responsive grids

---

## 🎯 **Future Enhancements**

### Planned Features:
- [ ] Direct messaging system
- [ ] Live notifications (WebSockets)
- [ ] Video calls
- [ ] Story highlights
- [ ] Post sharing/reposting
- [ ] Hashtag trending system
- [ ] Mentions system (@username)
- [ ] Email notifications
- [ ] Two-factor authentication
- [ ] Account verification badges
- [ ] Advanced search filters
- [ ] Export user data
- [ ] Dark mode scheduling
- [ ] Custom themes
- [ ] API for mobile apps

---

## 🛠️ **Installation & Setup**

### Prerequisites:
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js & NPM
- XAMPP (or similar)

### Installation Steps:

1. **Clone Repository:**
```bash
cd C:\xampp\htdocs\
git clone https://github.com/rupam0007/Blogging-App.git
cd Blogging-App
```

2. **Install Dependencies:**
```bash
composer install
npm install
```

3. **Environment Configuration:**
```bash
copy .env.example .env
php artisan key:generate
```

4. **Database Setup:**
- Create MySQL database: `blogging_app`
- Update `.env` file:
```
DB_DATABASE=blogging_app
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations:**
```bash
php artisan migrate
```

6. **Storage Link:**
```bash
php artisan storage:link
```

7. **Build Assets:**
```bash
npm run build
```

8. **Start Server:**
```bash
php artisan serve
```

9. **Visit:** `http://127.0.0.1:8000`

---

## 👤 **User Roles**

### **Regular User:**
- Create and manage own posts
- Create and delete own stories
- Comment on posts
- React to posts and stories
- Follow/unfollow users
- Bookmark posts
- Edit own profile
- View all public content

### **Admin:**
- All user permissions
- Access admin dashboard
- View statistics
- Manage all users
- Delete any post
- Delete any comment
- Ban/unban users
- Promote users to admin

---

## 📊 **Statistics & Analytics**

**Dashboard Statistics:**
- Total posts created
- Total followers count
- Total following count
- Active stories count
- Bookmarked posts count
- Notifications count

**Admin Dashboard:**
- Total registered users
- Total published posts
- Total draft posts
- Active stories (24h)
- Total comments
- Total reactions

---

## 🎓 **Best Practices Implemented**

1. **Code Organization:**
   - MVC architecture
   - Service providers
   - Middleware for auth
   - Request validation
   - Resource controllers

2. **Security:**
   - CSRF tokens
   - Password hashing
   - Input sanitization
   - Authorization gates
   - Rate limiting

3. **Database:**
   - Foreign key constraints
   - Indexes on frequently queried columns
   - Soft deletes (future)
   - Database transactions
   - Query optimization

4. **UI/UX:**
   - Consistent design language
   - Loading states
   - Error messages
   - Success feedback
   - Responsive layout

---

## 📄 **Credits**

**Developed by:** Rupam Giri (rupam0007)
**Framework:** Laravel 11
**Frontend:** Tailwind CSS
**Icons:** Google Material Symbols
**Repository:** https://github.com/rupam0007/Blogging-App

---

## 📞 **Support**

For issues or questions:
- GitHub Issues: https://github.com/rupam0007/Blogging-App/issues
- Email: support@bloggingapp.com (if configured)

---

## 📜 **License**

This project is open-source and available under the MIT License.

---

**Last Updated:** November 26, 2025
**Version:** 1.0.0
**Status:** Production Ready ✅
