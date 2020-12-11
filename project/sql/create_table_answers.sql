
CREATE TABLE IF NOT EXISTS Answers
(
    id          int auto_increment,
    answer      varchar(120) not null,

    modified    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP on update current_timestamp,
    question_id int,
    primary key (id),
    FOREIGN KEY (question_id) REFERENCES Questions (id)
)