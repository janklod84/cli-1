<?php
    // Программа для хранения контактов

    // Подключем класс базы данных
    include "db.php";
    // Настройка программы
    class set {
        public $bdset = array(
            'host' => 'localhost', // Хост сервера базы данных
            'user' => 'root',       // Логин для доступа к БД
            'pass' => 'root',// Пароль для доступа к БД
            'db'   => 'cont',       // Название базы данных
         );
        public $q = "hello";
    }
    // Класс обработки действий
    class action {
        // Функция показа контакта (контактов)
        public function view(){
            // Создаём объект класса настроек
            $sets = new set();
            // Создаём объект класса базы данных и указываем ему параметры
            $db = new SafeMySQL($sets->bdset);
            $data = $db->getAll("SELECT * FROM contacts");
            foreach ($data as $key) {
                echo "==============================================\n";
                echo "Идентификатор: ".$key["id"]."\n";
                echo "ФИО:           ".$key["name"]."\n";
                echo "Телефон:       ".$key["tel"]."\n";
                echo "Email:         ".$key["email"]."\n";
                echo "Комментарий:   ".$key["comment"]."\n";
                echo "==============================================\n";
            }
        }
        // Функция добавления контакта
        public function add() {
            // Создаём объект класса настроек
            $sets = new set();
            // Создаём объект класса базы данных и указываем ему параметры
            $db = new SafeMySQL($sets->bdset);
            // Задаём вопросы и записываем данные в переменные
            echo "Введите имя контакта: \n";
            $addname = readline();
            echo "Введите телефон контакта: \n";
            $addtel = readline();
            echo "Введите email контакта: \n";
            $addemail = readline();
            echo "Введите комментарий к контакту: \n";
            $addcomment = readline();

            // Добавляем в базу
            $db->query("INSERT INTO contacts (name, tel, email, comment) VALUES (?s, ?s, ?s, ?s)", $addname, $addtel, $addemail, $addcomment);
            echo "Запись добавлена \n";
            // Показываем список всех контактов
            $do = new action();
            $do->view();
        }
        // Функция удаления контакта
        public function delete($id){
            echo "Вы действительно хотите удалить контакт #".$id."? (y/n) \n";
            $del = readline();
            if ($del == "y") {
                // Создаём объект класса настроек
                $sets = new set();
                // Создаём объект класса базы данных и указываем ему параметры
                $db = new SafeMySQL($sets->bdset);
                // Удаляем контакт из базы
                $db->query("DELETE FROM contacts WHERE id = ?i", $id);
                echo "Запись удалена \n";
                // Показываем список всех контактов
                $do = new action();
                $do->view();
            }
            else echo "Ну нет, так нет. \n";

        }
        // Функция редактирования контакта
        public function edit($id) {
            // Создаём объект класса настроек
            $sets = new set();
            // Создаём объект класса базы данных и указываем ему параметры
            $db = new SafeMySQL($sets->bdset);
            // Задаём вопросы и записываем данные в переменные
            echo "Введите имя контакта: \n";
            $editname = readline();
            echo "Введите телефон контакта: \n";
            $edittel = readline();
            echo "Введите email контакта: \n";
            $editemail = readline();
            echo "Введите комментарий к контакту: \n";
            $editcomment = readline();

            // Обновляем запись
            $db->query("UPDATE contacts SET name = ?s, tel = ?s, email = ?s, comment = ?s WHERE id = ?i", $editname, $edittel, $editemail, $editcomment, $id);
            echo "Запись обновлена \n";
            // Показываем список всех контактов
            $do = new action();
            $do->view();
        }
        // Функция показа справки
        public function help() {
            echo "==============================================\n";
            echo "Справка по программе\n";
            echo "==============================================\n";
            echo "php contacts.php view - вывод всех контактов\n";
            echo "php contacts.php view 3 - вывод контакта с определённым идентификатором\n";
            echo "php contacts.php add - добавление контакта\n";
            echo "php contacts.php delete 3 - удаление контакта с определённым идентификатором\n";
            echo "php contacts.php help - показать эту подсказку\n";
        }

    }

    system("clear"); //Очищаем экран перед выводом
    // Шапка программы (там написано contacts)
    echo "             _           _\n";
    echo " ___ ___ ___| |_ ___ ___| |_ ___\n";
    echo "|  _| . |   |  _| .'|  _|  _|_ -|\n";
    echo "|___|___|_|_|_| |__,|___|_| |___|\n";
    echo "Надёжное хранение ваших контактов\n";
    // Выполнение действия, в зависимости от параметра
    switch ($argv[1]) {
        case 'view':
            $do = new action();
            $do->view();
            break;
        case 'edit':
            if (isset($argv[2])) {
                $do = new action();
                $do ->edit($argv[2]);
            }
            echo "Ошибка параметра, воспользуйтесь справкой \n";
            break;
        case 'add':
            $do = new action();
            $do ->add();
            break;
        case 'delete':
            if (isset($argv[2])) {
                $do = new action();
                $do ->delete($argv[2]);
            }
            echo "Ошибка параметра, воспользуйтесь справкой \n";
            break;
        case 'help':
            $do = new action();
            $do ->help();
            break;

        default:
            // Если параметр нам не подходит, или его нет, говорим об ошибке
            echo "Ошибка параметра, воспользуйтесь справкой \n";
            break;
    }
    echo "\n";