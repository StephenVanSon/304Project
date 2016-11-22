create table users (
    studentNum NUMBER(8) PRIMARY KEY,
    email      VARCHAR(15),
    password   VARCHAR(10),
    uname      VARCHAR(10)
);

create table sellers (
    studentNum NUMBER(8) NOT NULL,
    PRIMARY KEY (studentNum),
    FOREIGN KEY (studentNum) REFERENCES users
        ON DELETE NO ACTION
        ON UPDATE CASCADE
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
    FOREIGN KEY (ISBN) REFERENCES textbooks
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    FOREIGN KEY (courseCode, courseNum) REFERENCES course
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);

create table posting (
    postID      NUMBER(8),
    price       NUMBER(8) CHECK (price >= 0),
    description VARCHAR(1000),
    sold        VARCHAR(1),
    ISBN        NUMBER(13) NOT NULL,
    studentNum  NUMBER(8) NOT NULL,
    timePosted  TIMESTAMP(6),
    PRIMARY KEY (postID),
    FOREIGN KEY (ISBN) REFERENCES textbooks
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    FOREIGN KEY (studentNum) REFERENCES sellers
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);

create table sellers_sell_textbook (
    studentNum NUMBER(8) NOT NULL,
    ISBN       NUMBER(13) NOT NULL,
    PRIMARY KEY (studentNum, ISBN),
    FOREIGN KEY (studentNum) REFERENCES sellers
        ON DELETE NO ACTION
        ON UPDATE CASCADE,
    FOREIGN KEY (ISBN) REFERENCES textbooks
        ON DELETE NO ACTION
        ON UPDATE CASCADE
);

insert into users
values(12345678, 'exemail1@gmail.com', 'coconuts',
'example1');
 
insert into users
values (87654321, 'exemail2@gmail.com', 'bananas',
'example2', );
 
insert into users
values(13579246, 'exemail3@gmail.com', 'apples',
'example3');
 
insert into users
values(97531246, 'exemail4@gmail.com', 'oranges',
'example4');
 
insert into users
values(90863758, 'exemail5@gmail.com', 'limes',
'example5');
 
insert into sellers
values(12345678);


insert into sellers
values(87654321);


insert into sellers
values(13579246);


insert into sellers
values(97531246);


insert into sellers
values(90863758);


insert into textbooks
values(9780072465631, 'http://pages.cs.wisc.edu/~dbbook/images/book3ed.jpg', 'Database Management Systems 3/E');


insert into textbooks
values(9780262017350, 'https://shop.bookstore.ubc.ca/images/Product/icon/57440.jpg', 'Foundations of 3D Computer Graphics');


insert into textbooks
values(9780133571769, 'https://shop.bookstore.ubc.ca/images/Product/icon/61644.jpg', 'Strategic Staffing 3/E');


insert into textbooks
values(9780323355636, 'https://shop.bookstore.ubc.ca/images/Product/icon/85877.jpg', 'Textbook of Histology');


insert into textbooks
values(9780321776419, 'https://shop.bookstore.ubc.ca/images/Product/icon/74836.jpg', 'Progamming in C');


insert into course
values('CPSC', 304);


insert into course
values('CPSC', 314);


insert into course
values('COHR', 303);


insert into course
values('CAPS', 390);


insert into course
values('APSC', 160);


insert into course_of_textbook
values(9780072465631, 'CPSC', 304);


insert into course_of_textbook
values(9780262017350, 'CPSC', 314);

insert into course_of_textbook
values(9780133571769, 'COHR', 303);

insert into course_of_textbook
values(9780323355636, 'CAPS', 390);

insert into course_of_textbook
values(9780321776419, 'APSC', 160);

