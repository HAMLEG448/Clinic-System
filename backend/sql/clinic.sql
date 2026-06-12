CREATE DATABASE IF NOT EXISTS clinic_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE clinic_db;

CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    citizen_id VARCHAR(13) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    gender ENUM('male', 'female', 'other'),
    birth_date DATE,
    age INT,
    phone VARCHAR(20) NOT NULL UNIQUE,
    address TEXT,
    allergy TEXT,
    underlying_disease TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_patients_full_name (first_name, last_name)
);

CREATE TABLE visits (
    visit_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    visit_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    chief_complaint VARCHAR(255),
    symptom_detail TEXT,
    blood_pressure VARCHAR(50),
    temperature DECIMAL(4,1),
    weight DECIMAL(5,2),
    status ENUM('waiting', 'examining', 'completed', 'cancelled') DEFAULT 'waiting',

    active_patient_id INT GENERATED ALWAYS AS (
        CASE
            WHEN status IN ('waiting', 'examining') THEN patient_id
            ELSE NULL
        END
    ) STORED,

    UNIQUE KEY uq_active_visit_patient (active_patient_id),

    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

CREATE TABLE medical_records (
    medical_record_id INT AUTO_INCREMENT PRIMARY KEY,
    visit_id INT NOT NULL,
    diagnosis VARCHAR(255),
    physical_exam TEXT,
    treatment_plan TEXT,
    doctor_note TEXT,
    follow_up_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (visit_id) REFERENCES visits(visit_id)
);

CREATE TABLE medicines (
    medicine_id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_name VARCHAR(150) NOT NULL UNIQUE,
    medicine_type VARCHAR(50),
    unit VARCHAR(50),
    stock_qty INT DEFAULT 0,
    price DECIMAL(10,2) DEFAULT 0,
    expiry_date DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    visit_id INT NOT NULL,
    service_total DECIMAL(10,2) DEFAULT 0,
    medicine_total DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) DEFAULT 0,
    discount DECIMAL(10,2) DEFAULT 0,
    net_amount DECIMAL(10,2) DEFAULT 0,
    payment_method ENUM('cash', 'transfer', 'card') DEFAULT 'cash',
    payment_status ENUM('unpaid', 'paid', 'cancelled') DEFAULT 'unpaid',
    paid_at DATETIME,
    FOREIGN KEY (visit_id) REFERENCES visits(visit_id)
);