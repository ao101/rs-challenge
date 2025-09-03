# Development notes

System uses the Sqlite3 storage engine and Doctrine ORM.

System is upgraded to the latest version 7.3. It uses `ObjectMapper` component marked as experimental. 

The architecture of the developed system doesn't resemble the domain model data found in the `request.json`. This is done on purpose as an excercise of a real-world scenario where incoming data often doesn't map directly to the system's persistence model.

The architecture uses word "produce" as an abstraction of domain models `Fruit` and `Vegetable`. Produce by dictionary definition stands for *agricultural and other natural products collectively*.

Task description was clear about domain models, therefore, by following the **YAGNI** principle, system works with two persistence models: `Fruit` and `Vegetable`. System can easily be updated in order to add new categories both horizontally (other produce like `fish`, `meat`) and vertically (other categories like `cars`, `clothes`).

### How to use

* `GET` to`/produce` endpoint lists all collections items. It accepts following filters: `name`, `type`, `weight`, `minWeight`, `maxWeight`, `unit`, `search` query paramters.
* `POST` to `/produce` accepts array of objects, e.g. provided `request.json`, creates collections and saves them to the database.

### System Features
1. Names, categories, and units are mapped from request data to appropriate types to ensure data integrity. Example: the system detects an error in `request.json` where *lettuce* is incorrectly categorized as a *fruit*.
2. Request data can be parsed with **hard** or **soft fails**, where the system either **aborts processing** or **continues with validated data**, respectively.
3. Since the system uses Doctrine ORM as a core component, `Fruit` and `Vegetable` collections are built on top of Doctrine's `ArrayCollection` class. This class is extended to add `list()` and `search()` methods.
4. Collections have type checking so that `Fruit` can't end up in a  `VegetableCollection`.
5. By using `ArrayCollection`, a single filtering service is developed and can be used for querying both in-memory collections (`$collection->matching($filter)`) and the database (`$repository->matching($filter)`).
6. Weight units are normalized.  Example: `/produce?weight=200000&unit=g` gives the same result as `/produce?weight=20&unit=kg`.
7. The system is extensible in terms of units and names. It will continue to function if new weight units (e.g. `mg`, `t`) or produce names (e.g. `Zucchinis`, `Raspberries`) are introduced.



# 🍎🥕 Fruits and Vegetables

## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving an option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embed your logic 

### ✔️ How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run `bin/phpunit` from the console)

or

* You create a Controller which will be calling the service with a json payload

## 💡 Hints before you start working on it
* Keep KISS, DRY, YAGNI, SOLID principles in mind
* We value a clean domain model, without unnecessary code duplication or complexity
* Think about how you will handle input validation
* Follow generally-accepted good practices, such as no logic in controllers, information hiding (see the first hint).
* Timebox your work - we expect that you would spend between 3 and 4 hours.
* Your code should be tested
* We don't care how you handle data persistence, no bonus points for having a complex method

## When you are finished
* Please upload your code to a public git repository (i.e. GitHub, Gitlab)

## 🐳 Docker image
Optional. Just here if you want to run it isolated.

### 📥 Pulling image
```bash
docker pull tturkowski/fruits-and-vegetables
```

### 🧱 Building image
```bash
docker build -t tturkowski/fruits-and-vegetables -f docker/Dockerfile .
```

### 🏃‍♂️ Running container
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables sh 
```

### 🛂 Running tests
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables bin/phpunit
```

### ⌨️ Run development server
```bash
docker run -it -w/app -v$(pwd):/app -p8080:8080 tturkowski/fruits-and-vegetables php -S 0.0.0.0:8080 -t /app/public
# Open http://127.0.0.1:8080 in your browser
```

