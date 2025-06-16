from datetime import datetime, timedelta
import sqlite3

def generate_schedule_entries(start_train_id, end_train_id, from_station, to_station, updated_by):
    base_departure_times = ['08:00:00', '10:00:00', '12:00:00', '14:00:00', '16:00:00', '18:00:00', '20:00:00']
    schedule_entries = []
    
    for train_id in range(start_train_id, end_train_id + 1):
        for time in base_departure_times:
            dep_time = datetime.strptime(time, "%H:%M:%S")
            arr_time = dep_time + timedelta(minutes=45)
            
            # Forward journey
            schedule_entries.append((
                train_id,
                from_station,
                to_station,
                dep_time.time().strftime('%H:%M:%S'),
                arr_time.time().strftime('%H:%M:%S'),
                'daily',
                updated_by
            ))
            
            # Return journey
            return_dep = dep_time + timedelta(minutes=30)
            return_arr = return_dep + timedelta(minutes=45)
            schedule_entries.append((
                train_id,
                to_station,
                from_station,
                return_dep.time().strftime('%H:%M:%S'),
                return_arr.time().strftime('%H:%M:%S'),
                'daily',
                updated_by
            ))
    
    return schedule_entries

def insert_schedules_into_db(db_path, schedule_entries, make_inactive=None):
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    try:
        # Mark specific train as inactive if specified
        if make_inactive:
            cursor.execute("""
                UPDATE trains 
                SET status = 'maintenance', 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE train_id = ?
            """, (make_inactive,))
            print(f"Marked train {make_inactive} as 'maintenance'")
        
        # Check if schedules table exists, create if not
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS schedules (
                schedule_id INTEGER PRIMARY KEY AUTOINCREMENT,
                train_id INTEGER NOT NULL,
                from_station INTEGER NOT NULL,
                to_station INTEGER NOT NULL,
                departure_time TEXT NOT NULL,
                arrival_time TEXT NOT NULL,
                frequency TEXT NOT NULL,
                updated_by INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (train_id) REFERENCES trains(train_id),
                FOREIGN KEY (updated_by) REFERENCES users(user_id)
            )
        """)
        
        # Insert schedule entries
        cursor.executemany("""
            INSERT INTO schedules 
            (train_id, from_station, to_station, departure_time, arrival_time, frequency, updated_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        """, schedule_entries)
        
        conn.commit()
        print(f"Successfully inserted {len(schedule_entries)} schedule entries")
        
    except sqlite3.Error as e:
        print(f"Database error: {e}")
        conn.rollback()
    finally:
        conn.close()

# Configuration
DB_PATH = 'orange_line_project.db'
START_TRAIN_ID = 9
END_TRAIN_ID = 27
FROM_STATION = 1  # Adjust based on your station IDs
TO_STATION = 11   # Adjust based on your station IDs
UPDATED_BY = 1    # User ID who is making these updates
MAKE_INACTIVE = 12 # Train ID to mark as maintenance

# Generate and insert schedules
schedule_entries = generate_schedule_entries(
    start_train_id=START_TRAIN_ID,
    end_train_id=END_TRAIN_ID,
    from_station=FROM_STATION,
    to_station=TO_STATION,
    updated_by=UPDATED_BY
)

insert_schedules_into_db(DB_PATH, schedule_entries, MAKE_INACTIVE)