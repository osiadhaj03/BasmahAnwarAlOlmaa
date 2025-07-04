# BasmahApp Check-In System Implementation

## Overview
The check-in system allows students to register their attendance for lessons through a URL-based interface.

## URL Format
```
/check-in?lesson=ID&student=ID
```

## Implementation Logic

### 1. Security Validation
- ✅ Verify student is authenticated and has 'student' role
- ✅ Validate lesson ID and student ID are provided
- ✅ Ensure student ID matches authenticated user

### 2. Enrollment Verification
- ✅ Confirm student is registered in the requested lesson
- ✅ Uses lesson_student pivot table relationship

### 3. Day Validation
- ✅ Check if today matches the lesson's day_of_week
- ✅ Prevents check-in on wrong days

### 4. Time-Based Attendance Rules
- ✅ **First 15 minutes**: Mark as 'present'
- ✅ **After 15 minutes but before end**: Mark as 'late' with minutes delay
- ✅ **After end time**: Reject check-in
- ✅ **Before start time**: Reject check-in

### 5. Duplicate Prevention
- ✅ Check for existing attendance record for same date
- ✅ Prevent multiple check-ins per day per lesson

### 6. Database Storage
- ✅ Store in attendances table with:
  - student_id
  - lesson_id  
  - date (today)
  - status ('present' or 'late')
  - notes (optional details like delay time)

## Database Schema

### attendances table
```sql
- id (primary)
- student_id (foreign key to users)
- lesson_id (foreign key to lessons)
- date (date)
- status (enum: present, absent, late, excused)
- notes (text, nullable)
- timestamps
- unique constraint (student_id, lesson_id, date)
```

### lessons table (relevant fields)
```sql
- day_of_week (string: monday, tuesday, etc.)
- start_time (time)
- end_time (time)
- schedule_time (time, for display)
```

## Test Scenarios

### Success Cases
1. **On-time check-in**: Within first 15 minutes → status: 'present'
2. **Late check-in**: After 15 minutes but before end → status: 'late'

### Rejection Cases
1. **Wrong day**: Lesson scheduled for different day
2. **Too early**: Before lesson start time
3. **Too late**: After lesson end time
4. **Already checked in**: Duplicate attempt
5. **Not enrolled**: Student not registered in lesson
6. **Invalid data**: Missing or wrong lesson/student IDs

## Usage Example

### Student Dashboard
- Shows today's lessons with check-in buttons
- Displays lesson time and teacher information
- Button disabled if already checked in

### Check-in Process
1. Student clicks "تسجيل الحضور" button
2. Redirected to `/check-in?lesson=1&student=4`
3. System validates all conditions
4. Creates attendance record or shows error
5. Redirects back to dashboard with status message

## Implementation Files

### Controllers
- `app/Http/Controllers/Student/StudentController.php`
  - `dashboard()` method: Shows available lessons
  - `checkIn()` method: Handles attendance logic

### Models
- `app/Models/User.php`: Added `lessons()` relationship
- `app/Models/Lesson.php`: Existing relationships
- `app/Models/Attendance.php`: Updated with notes field

### Views
- `resources/views/student/dashboard.blade.php`: Check-in interface
- `resources/views/layouts/student.blade.php`: Student layout

### Routes
- `routes/web.php`: Student routes with middleware protection

### Middleware
- `app/Http/Middleware/StudentMiddleware.php`: Role-based access control

## Testing Data
- Sample lessons for different days of the week
- 10 test students (student1@basmahapp.com - student10@basmahapp.com)
- Lessons with realistic timings for testing different scenarios

## Status Messages
- **Success**: "تم تسجيل حضورك بنجاح!" or "تم تسجيل حضورك كمتأخر"
- **Already checked in**: "تم تسجيل حضورك مسبقاً لهذا الدرس اليوم"
- **Wrong day**: "لا يمكن تسجيل الحضور اليوم - هذا الدرس مجدول يوم X"
- **Time violations**: "لا يمكن تسجيل الحضور - انتهى وقت الدرس"
- **Not enrolled**: "غير مسموح لك بتسجيل الحضور في هذا الدرس - لست مسجلاً فيه"
