create table users (
    studentNum NUMBER(8) PRIMARY KEY,
    email      VARCHAR(50),
    password   VARCHAR(20),
    uname      VARCHAR(20)
);


create table sellers (
    studentNum NUMBER(8) NOT NULL,
    PRIMARY KEY (studentNum),
    FOREIGN KEY (studentNum) REFERENCES users
);


create table textbooks (
    ISBN       NUMBER(13) PRIMARY KEY,
    image      VARCHAR(100),
    title      VARCHAR(50)
);


create table authors_in_textbook (
    aname      VARCHAR(15),
    ISBN       NUMBER(13) PRIMARY KEY
        REFERENCES textbooks(ISBN)
        ON DELETE CASCADE
);


create table course (
    courseCode VARCHAR(4) NOT NULL,
    courseNum  NUMBER(3) NOT NULL,
    PRIMARY KEY (courseCode, courseNum)
);


create table course_of_textbook (
    ISBN        NUMBER(13) NOT NULL,
    courseCode  VARCHAR(4) NOT NULL,
    courseNum   NUMBER(3) NOT NULL,
    PRIMARY KEY (ISBN, courseCode, courseNum),
    FOREIGN KEY (ISBN) REFERENCES textbooks,
    FOREIGN KEY (courseCode, courseNum) REFERENCES course
);


create table posting (
    postID      NUMBER(8),
    price       NUMBER(8),
    description VARCHAR(1000),
    image       VARCHAR(100),
    sold        VARCHAR(1),
    ISBN        NUMBER(13) NOT NULL,
    studentNum  NUMBER(8) NOT NULL,
    PRIMARY KEY (postID),
    FOREIGN KEY (ISBN) REFERENCES textbooks,
    FOREIGN KEY (studentNum) REFERENCES sellers
);


create table sellers_sell_textbook (
    studentNum NUMBER(8) NOT NULL,
    ISBN       NUMBER(13) NOT NULL,
    PRIMARY KEY (studentNum, ISBN),
    FOREIGN KEY (studentNum) REFERENCES sellers,
    FOREIGN KEY (ISBN) REFERENCES textbooks
);
