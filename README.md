# Acme Widget Co. Shopping Basket

## Description

This project implements a shopping basket system for Acme Widget Co. It allows users to add products to a basket and calculates the total cost, including delivery charges and applicable special offers.

## Features

*   **Product Catalog:** Manages a list of available widgets (Red, Green, Blue).
*   **Shopping Basket:** Allows adding products to a virtual basket.
*   **Dynamic Pricing:**
    *   Calculates subtotal of items in the basket.
    *   Applies special offers and discounts.
    *   Calculates delivery charges based on the total after discounts.
*   **Extensible:** Designed with services for products, delivery charges, and special offers.

## Tech Stack

*   PHP 8.2
*   Composer (for dependency management)
*   Docker & Docker Compose (for containerized development environment)
*   PHPUnit (for automated testing)

## Prerequisites

Before you begin, ensure you have the following installed:
*   [Docker](https://www.docker.com/get-started)
*   [Docker Compose](https://docs.docker.com/compose/install/) (usually included with Docker Desktop)

## Getting Started

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/bigpreshy/a_widget.git
    cd a_widget
    ```

2.  **Build and start services:**
    The `docker-compose.yml` is set up to run a PHP application container and a Composer service. To start the application container (though it currently just runs `php -v` to keep alive):
    ```bash
    docker-compose up -d app
    ```
    This will start the `app` service in detached mode.

3.  **Install PHP dependencies:**
    Use the Composer service defined in `docker-compose.yml` to install dependencies:
    ```bash
    docker-compose run --rm composer install
    ```

## Usage

(This section might need more details depending on how the application is intended to be used, e.g., as a library, a CLI application, or part of a web service. For now, it describes the core functionality.)

The primary functionality revolves around the `AcmeWidgetCo\Basket` class. It can be instantiated and used to add products and calculate totals.

**Example (Conceptual):**

```php
<?php
use AcmeWidgetCo\Basket;
use AcmeWidgetCo\Foundation\ProductCollection;
use AcmeWidgetCo\Services\ProductService;
use AcmeWidgetCo\Services\DeliveryChargeService;
use AcmeWidgetCo\Services\SpecialOfferDiscountService;
use AcmeWidgetCo\Repositories\SpecialOfferRepository;

$productService = new ProductService();
$deliveryRulesRepo = new DeliveryChargeRuleRepository();
$deliveryChargeService = new DeliveryChargeService($deliveryRulesRepo);
$specialOfferRepo = new SpecialOfferRepository();
$specialOfferDiscountService = new SpecialOfferDiscountService();

$basket = new Basket(
    new ProductCollection(),
    $deliveryChargeService,
    $specialOfferDiscountService,
    $productService,
    $specialOfferRepo
);

$basket->add('R01');
$basket->add('G01');

$totalCostInCents = $basket->total();
echo "Total cost: $" . number_format($totalCostInCents / 100, 2) . PHP_EOL;
?>
```

## Project Assumptions

This project operates under the following key assumptions:

*   **Currency Handling:** All monetary values (product prices, discounts, delivery charges) are managed internally as integer values representing cents to ensure precision. The specific currency (e.g., USD) is implied and assumed to be consistent.
*   **Product Data Source:** Product information (codes, names, prices) is currently provided by the `ProductService` with a static dataset. In a production system, this would typically be sourced from a database or an external Product Information Management (PIM) system.
*   **Delivery Charge Logic:** Delivery charges are calculated based on a set of rules retrieved via the `DeliveryChargeRuleRepositoryInterface`. The definition and storage of these rules are abstracted by this interface.
*   **Special Offer Mechanism:** Special offers are fetched through the `SpecialOfferRepository` and their application logic is encapsulated within the `SpecialOfferDiscountService`.
*   **Development Environment:** The primary environment for development, testing, and analysis is Docker, orchestrated using the provided `docker-compose.yml`.
*   **Input Validity:** While basic checks are in place (e.g., for product codes), the system generally assumes that inputs to its services and methods conform to expected types and formats. More robust validation might be necessary for a production deployment.

## Running Tests

To run the automated tests using PHPUnit, use the Composer service:

```bash
docker-compose run --rm composer test
```
This command executes the test suite defined in `phpunit.xml`.

### Static Analysis (PHPStan)

To perform static analysis on the codebase using PHPStan:

```bash
docker-compose run --rm composer analyze
```
This command utilizes the `analyze` script defined in `composer.json` to check the `src` and `tests` directories.