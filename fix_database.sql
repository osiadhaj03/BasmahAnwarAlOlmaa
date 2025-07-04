-- Add missing columns to lessons table
ALTER TABLE lessons ADD COLUMN IF NOT EXISTS name VARCHAR(255) AFTER id;
ALTER TABLE lessons ADD COLUMN IF NOT EXISTS description TEXT NULL AFTER end_time;
ALTER TABLE lessons ADD COLUMN IF NOT EXISTS schedule_time TIME NULL AFTER description;

-- Add notes column to attendances table
ALTER TABLE attendances ADD COLUMN IF NOT EXISTS notes TEXT NULL AFTER status;

-- Update existing lessons with default names if they don't have them
UPDATE lessons SET name = CONCAT(subject, ' - الدرس') WHERE name IS NULL OR name = '';

-- Show updated structure
DESCRIBE lessons;
DESCRIBE attendances;
