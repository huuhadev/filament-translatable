# Filament Translatable Plugin

## Installation

Install the plugin with Composer:

```bash
composer require huuhadev/filament-translatable
```

After the package is installed, if you wish to use flag images, you can publish the assets using the following command:
```bash
php artisan filament-translatable:install
```

## Adding the plugin to a panel

To add a plugin to a panel.

```php
use HuuHaDev\FilamentTranslatable\FilamentTranslatablePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentTranslatablePlugin::make()
                ->locales([
                    'en' => asset('/vendor/filament-translatable/assets/flags/gb.svg'),
                    'ar' => asset('/vendor/filament-translatable/assets/flags/ar.svg')
                ])
                ->onlyFlag() // Default: false
                ->rounded(false) // Default: true
                ->renderHook('panels::global-search.before') // Default: 'panels::global-search.after'
                ->displayLocale('ar') // Display locale as an optional parameter to get the language names in a specific language. If no display locale is specified, the application's current locale is used.
                ->labels(['en' => 'English (EN)', 'ar' => 'Arabic (AR)']) // Custom text labels for each locale that your application supports. Default use PHP's native function locale_get_display_name()
                ->size('lg') // By default, the avatar will be "medium" size. You can set the size to either sm, md, or lg using the size attribute: `w-8 h-8`
                ->buttonClasses('rounded-full') // Default: 'rounded-lg',
        ])
}
```
## Translate Action
This only supports TextInput, Textarea, and RichEditor fields.
```php
TextInput::make('name')
    ->translator('Auto translate') // This line to make field translatable,
```

## Preparing your model class

You need to make your model translatable. You can read how to do this in [Spatie's documentation](https://spatie.be/docs/laravel-translatable/installation-setup#content-making-a-model-translatable).

## Preparing your resource class

You must apply the `Huuhadev\FilamentTranslatable\Concerns\ResourcesTranslatable` trait to your resource class:

```php
use HuuHaDev\FilamentTranslatable\Concerns\ResourcesTranslatable;
use Filament\Resources\Resource;

class PostResource extends Resource
{
    use ResourcesTranslatable;
    
    // ...
}
```

## Making resource pages translatable

```php
use Filament\Resources\Pages\ListRecords;
use HuuHaDev\FilamentTranslatable\Actions\SelectLocale;
use HuuHaDev\FilamentTranslatable\Concerns\ListRecordsTranslatable;

class ListPosts extends ListRecords
{
    use ListRecordsTranslatable;
    
    protected function getHeaderActions(): array
    {
        return [
            SelectLocale::make(),
            // ...
        ];
    }
    
    // ...
}
```
## Translating Manager Records

```php
use Filament\Resources\Pages\ManageRecords;
use HuuHaDev\FilamentTranslatable\Actions\SelectLocale;
use HuuHaDev\FilamentTranslatable\Concerns\ManageRecordsTranslatable;

class ManageAuthors extends ManageRecords
{
    use ManageRecordsTranslatable;
    
    protected function getHeaderActions(): array
    {
        return [
            SelectLocale::make(),
            // ...
        ];
    }
    
    // ...
}
```
## Translating create page
```php
use Filament\Resources\Pages\CreateRecord;
use HuuHaDev\FilamentTranslatable\Actions\SelectLocale;
use HuuHaDev\FilamentTranslatable\Concerns\CreateRecordTranslatable;

class CreateBlogPost extends CreateRecord
{
    use CreateRecordTranslatable;
    
    protected function getHeaderActions(): array
    {
        return [
            SelectLocale::make(),
            // ...
        ];
    }
    
    // ...
}
```
## Translating edit page
```php
use Filament\Resources\Pages\EditRecord;
use HuuHaDev\FilamentTranslatable\Actions\SelectLocale;
use HuuHaDev\FilamentTranslatable\Concerns\EditRecordTranslatable;

class EditBlogPost extends EditRecord
{
    use EditRecordTranslatable;
    
    protected function getHeaderActions(): array
    {
        return [
            SelectLocale::make(),
            // ...
        ];
    }
    
    // ...
}
```
## Translating view page
```php
use Filament\Resources\Pages\ViewRecord;
use HuuHaDev\FilamentTranslatable\Actions\SelectLocale;
use HuuHaDev\FilamentTranslatable\Concerns\ViewRecordTranslatable;

class ViewBlogPost extends ViewRecord
{
    use ViewRecordTranslatable;
    
    protected function getHeaderActions(): array
    {
        return [
            SelectLocale::make(),
            // ...
        ];
    }
    
    // ...
}
```


### Setting the translatable locales for a particular resource

Customize the translatable locales for a particular resource by overriding the `getTranslatableLocales()` method in your resource class:

```php
use Filament\Resources\Resource;
use HuuHaDev\FilamentTranslatable\Concerns\ResourceTranslatable;

class PostResource extends Resource
{
    use ResourceTranslatable;
    
    // ...
    
    public static function getTranslatableLocales(): array
    {
        return ['en', 'fr'];
    }
}
```

## Translating relation managers

```php
use Filament\Resources\RelationManagers\RelationManager;
use HuuHaDev\FilamentTranslatable\Concerns\RelationManagersTranslatable;

class PostsRelationManager extends RelationManager
{
    use RelationManagersTranslatable;
    
    // ...
}
```

Now, you can add a new `TableSelectLocale` action to the header of the relation manager's `table()`:

```php
use Filament\Tables\Table;
use HuuHaDev\FilamentTranslatable\Actions\Tables\TableSelectLocale;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->headerActions([
            // ...
            TableSelectLocale::make(),
        ]);
}
```

Override the `$activeLocale` property and add Livewire's `Reactive` attribute to it:

```php
use Filament\Resources\RelationManagers\RelationManager;
use Livewire\Attributes\Reactive;
use HuuHaDev\FilamentTranslatable\Concerns\RelationManagersTranslatable;

class PostsRelationManager extends RelationManager
{
    use RelationManagersTranslatable;
    
    #[Reactive]
    public ?string $activeLocale = null;
    
    // ...
}
```

If you do this, you no longer need a `SelectLocale` action in the `table()`.

### Setting the translatable locales for a particular relation manager

Customize the translatable locales for a particular relation manager by overriding the `getTranslatableLocales()` method in your relation manager class:

```php
use Filament\Resources\RelationManagers\RelationManager;
use HuuHaDev\FilamentTranslatable\Concerns\RelationManagersTranslatable;

class PostsRelationManager extends RelationManager
{
    use RelationManagersTranslatable;
    
    // ...
    
    public function getTranslatableLocales(): array
    {
        return ['en', 'ar'];
    }
}
```

### After the trait is applied on the model you can do these things:

```php
$newsItem = new NewsItem;
$newsItem
   ->setTranslation('name', 'en', 'Name in English')
   ->setTranslation('name', 'nl', 'Naam in het Nederlands')
   ->save();

$newsItem->name; // Returns 'Name in English' given that the current app locale is 'en'
$newsItem->getTranslation('name', 'nl'); // returns 'Naam in het Nederlands'

app()->setLocale('nl');

$newsItem->name; // Returns 'Naam in het Nederlands'

// If you want to query records based on locales, you can use the `whereLocale` and `whereLocales` methods.

NewsItem::whereLocale('name', 'en')->get(); // Returns all news items with a name in English

NewsItem::whereLocales('name', ['en', 'nl'])->get(); // Returns all news items with a name in English or Dutch

// Returns all news items that has name in English with value `Name in English` 
NewsItem::query()->whereJsonContainsLocale('name', 'en', 'Name in English')->get();

// Returns all news items that has name in English or Dutch with value `Name in English` 
NewsItem::query()->whereJsonContainsLocales('name', ['en', 'nl'], 'Name in English')->get();

// The last argument is the "operand" which you can tweak to achieve something like this:

// Returns all news items that has name in English with value like `Name in...` 
NewsItem::query()->whereJsonContainsLocale('name', 'en', 'Name in%', 'like')->get();

// Returns all news items that has name in English or Dutch with value like `Name in...` 
NewsItem::query()->whereJsonContainsLocales('name', ['en', 'nl'], 'Name in%', 'like')->get();
```

## Translator
### Default
By default, the package use Google Translator is the default, The plugin allows integration with other translation services. For integration, create a class that adheres to the `Huuhadev\FilamentTranslatable\Contracts\Translator` contract and update the `filament-translatable.php` config file accordingly.

### AWS Translate Integration
Ensure you've set the necessary configurations as specified in the [AWS Service Provider for Laravel](https://github.com/aws/aws-sdk-php-laravel) documentation, and have the following environment variables:

```dotenv
AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_REGION=your-region  # default is us-east-1
```
## Publishing
You can publish the views file with:
```bash
php artisan vendor:publish --tag=filament-translatable-views
```
You can publish the config file with:
```bash
php artisan vendor:publish --tag=filament-translatable-config
```
```bash
php artisan vendor:publish --tag=filament-translatable-translations
```

## Screenshots

![Language switcher](https://raw.githubusercontent.com/huuhadev/filament-translatable/master/art/ap-language-switcher.png)
![Content language switcher](https://raw.githubusercontent.com/huuhadev/filament-translatable/master/art/content-language-selector.png)
![Translator](https://raw.githubusercontent.com/huuhadev/filament-translatable/master/art/auto-translator.png)
![Google Translator](https://raw.githubusercontent.com/huuhadev/filament-translatable/master/art/google-translator.png)