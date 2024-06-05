-- Create logintbl table
CREATE TABLE logintbl (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    profile_pic VARCHAR(255),
    account_type INT -- Account type: 1 for admin, 2 for customer
);

-- Create admintbl table
CREATE TABLE admintbl (
    adminID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT UNIQUE,
    status VARCHAR(50),
    FOREIGN KEY (userID) REFERENCES logintbl(userID)
);

-- Create Vendor table
CREATE TABLE Vendor (
    VendorID INT AUTO_INCREMENT PRIMARY KEY,
    VendorName VARCHAR(100)
);

-- Create Invoice table
CREATE TABLE Invoice (
    InvoiceID INT AUTO_INCREMENT PRIMARY KEY,
    VendorID INT,
    InvoiceNumber VARCHAR(50),
    InvoiceDate DATE,
    DueDate DATE,
    AmountDue DECIMAL(10, 2),
    PaymentStatus VARCHAR(20),
    FOREIGN KEY (VendorID) REFERENCES Vendor(VendorID)
);

-- Create Customer table
CREATE TABLE Customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerName VARCHAR(100),
    userID INT UNIQUE, -- Connection to logintbl
    FOREIGN KEY (userID) REFERENCES logintbl(userID)
);

-- Create Trip table
CREATE TABLE Trip (
    TripID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT,
    TripDate DATE,
    TripFare DECIMAL(10, 2),
    PaymentStatus VARCHAR(20),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);
