.PHONY: phpstan cs cs-apply
.RECIPEPREFIX = |

all:
| @echo "usage: make"
| @echo "  [phpstan]     run phpstan checks"
| @echo "  [cs]          run symfony cs fixer checks"
| @echo "  [cs-apply]    run symfony cs fixer checks and fix errors"

phpstan:
| php -dmemory_limit=512M ./vendor/bin/phpstan analyse --level=7 src

cs:
| PHP_CS_FIXER_FUTURE_MODE=1 php ./vendor/bin/php-cs-fixer fix -v --dry-run --diff src

cs-apply:
| PHP_CS_FIXER_FUTURE_MODE=1 php ./vendor/bin/php-cs-fixer fix -v src
