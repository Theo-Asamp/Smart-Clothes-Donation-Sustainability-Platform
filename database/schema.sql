CREATE TABLE user (
    user_ID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL CHECK(role IN ('donor', 'staff', 'admin'))
);

CREATE TABLE donations (
    donations_ID INTEGER PRIMARY KEY AUTOINCREMENT,
    user_ID INTEGER NOT NULL,
    description TEXT NOT NULL,
    image_URL TEXT NOT NULL,
    condition TEXT NOT NULL DEFAULT 'worn' CHECK(condition IN ('new', 'worn')),
    status TEXT NOT NULL DEFAULT 'pending' CHECK(status IN ('pending', 'approved', 'declined')),
    FOREIGN KEY(user_ID) REFERENCES user(user_ID)
);

CREATE TABLE inventory (
    inventory_ID INTEGER PRIMARY KEY AUTOINCREMENT,
    donations_ID INTEGER NOT NULL,
    quantity INTEGER NOT NULL DEFAULT 1,
    status TEXT NOT NULL DEFAULT 'available' CHECK(status IN ('available', 'allocated')),
    FOREIGN KEY(donations_ID) REFERENCES donations(donations_ID)
);

CREATE TABLE distributions (
    distributions_ID INTEGER PRIMARY KEY AUTOINCREMENT,
    inventory_ID INTEGER NOT NULL,
    quantity INTEGER NOT NULL DEFAULT 1,
    beneficiary TEXT NOT NULL,
    FOREIGN KEY(inventory_ID) REFERENCES inventory(inventory_ID)
);
