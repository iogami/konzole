# konzole (Тестовое задание)
### Содержание
* [Как установить](#как-установить)
* [Настройка библиотеки](#настройка-библиотеки)
* [Добавление своих команд](#добавление-своих-команд)
* [Вызов библиотеки](#вызов-библиотеки)
* [Текст задания](#текст-задания)
### Как установить
Konzole можно установить, используя [Composer](https://getcomposer.org), достаточно выполнить эту команду в вашем проекте:
```
composer require iogami/konzole
```
### Настройка библиотеки
Эта библиотека автоматически ищет php-скрипты с командами в папке `Commands`, которая должна быть в той же директории, где и запускаемый 
скрипт.

Например, если мы запускаем команду `php app.php example {help}`, то папка `Commands` должна быть там же, где файл `app.php`

Также можно указать свою папку.

Самый простой способ - это передать путь в качестве аргумента при создании экземпляра класса Konzole:
```php
$console = new Konzole('path/to/Commands');
```

Для фреймворка symfony можно применить способ autowiring и указать в настройках
```yaml
# config\services.yaml

services:
  Konzole\Konzole:
    arguments:
      $commandsDirPath: 'path/to/Commands'
```

Также можно создать в проекте константу `COMMANDS_DIR`
```php
define('COMMANDS_DIR', 'path/to/Commands');
```

**Внимание! Путь до папки с командами надо указывать вместе с названием самой папки.**

### Вызов библиотеки
Простой пример вызова библиотеки:

```php
<?php

require_once('vendor/autoload.php');

use Konzole\Konzole;

$console = new Konzole();
// Your code here!!!
$console->execute();
```
### Добавление своих команд
Для добавления новой команды необходимо в папке `Commands` создать файл со структурой указанной ниже.

Если название команды состоит из 2-х слов и более, то название класса следует писать как `CommandName`, но при этом команду надо вызывать как
`command_name`.

Метод `help`, в созданном классе будет вызываться если при вызове команды будет передан параметр `{help}`.

```console
$/usr/bin/php app.php command_name {help}
```

Во всех остальных случаях будет вызываться метод `run`. Параметр `$params` в этом методе будет содержать параметры, указанные в консоли при 
вызове команды.

Также можно воспользоваться классом `Konzole\InputOutput\Output` для вывода данных в консоль.

```php
<?php

namespace Commands;

use Konzole\Command;

class Example extends Command {

    public function run(array $params = []): void
    {
        // TODO: Implement run() method.
    }

    public function help(): void
    {
        // TODO: Implement help() method.
    }
}
```
### Текст задания
Необходимо разработать библиотеку, обеспечивающую обработку ввода-вывода (I/O) при работе в консоли с возможностью реализации собственных команд конечным разработчиком, который использует эту библиотеку.

Требуется реализовать обработку входящих аргументов запуска в соответствии со следующим соглашением:
- название команды передается первым аргументом в произвольном формате;
- аргументы запуска передаются в фигурных скобках через запятую в следующем формате:
  - одиночный аргумент: `{arg}`
  - несколько аргументов: `{arg1,arg2,arg3}` ИЛИ `{arg1} {arg2} {arg3}` ИЛИ `{arg1,arg2} {arg3}`
- параметры запуска передаются в квадратных скобках в следующем формате:
  - параметр с одним значением: `[name=value]`
  - параметр с несколькими значениями: `[name={value1,value2,value3}]`

Функциональность библиотеки включает в себя:
- регистрацию необходимых команд в приложении;
- возможность установить название и описание каждой команды;
- обработку ввода пользователя (парсинг аргументов и выявление имени команды,
аргументов и параметров);
- выполнение заданной логики с возможностью вывода в информации в консоль.

При запуске приложения без указания конкретной команды необходимо выводить список
всех зарегистрированных в нём команд и их описания.

При запуске любой из команд с аргументом `{help}` необходимо выводить описание
команды.

### Пример работы конечной команды
```console
$/usr/bin/php app.php example {verbose,overwrite} [log_file=app.log] {unlimited} [methods={create,update,delete}] [paginate=50] {log}

Called command: command_name

Arguments:
  - verbose
  - overwrite
  - unlimited
  - log

Options:
  - log_file
    - app.log
  - methods
    - create
    - update
    - delete
  - paginate
    - 50
```