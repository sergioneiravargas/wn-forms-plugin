# Sntools Forms Plugin

WinterCMS plugin for dynamic forms creation. Comes with an API and mailing functionalities out-of-the-box.

## Mailing

The **sntoolsMailingList** array should be added to the mail config. Each item of the list is an array with the **name** and **address** of the receivers.

```
    'sntoolsMailingList' => [
        [
            'address' => 'example1@email.com',
            'name' => 'Firs Example',
        ],
        [
            'address' => 'example2@email.com',
            'name' => 'Second Example',
        ],
    ],
```