<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute باید پذیرفته شود.',
    'active_url'           => ':attribute یک URL معتبر نیست.',
    'after'                => ':attribute باید تاریخی بعد از :date باشد.',
    'after_or_equal'       => ':attribute باید تاریخی بعد از یا برابر با :date باشد.',
    'alpha'                => ':attribute فقط می‌تواند شامل حروف باشد.',
    'alpha_dash'           => ':attribute فقط می‌تواند شامل حروف، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num'            => ':attribute فقط می‌تواند شامل حروف و اعداد باشد.',
    'array'                => ':attribute باید یک آرایه باشد.',
    'before'               => ':attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal'      => ':attribute باید تاریخی قبل از یا برابر با :date باشد.',
    'between'              => [
        'numeric' => ':attribute باید بین :min و :max باشد.',
        'file'    => ':attribute باید بین :min و :max کیلوبایت باشد.',
        'string'  => ':attribute باید بین :min و :max کاراکتر باشد.',
        'array'   => ':attribute باید بین :min و :max آیتم داشته باشد.',
    ],
    'boolean'              => ':attribute باید true یا false باشد.',
    'confirmed'            => 'تأیید :attribute مطابقت ندارد.',
    'date'                 => ':attribute یک تاریخ معتبر نیست.',
    'date_equals'          => ':attribute باید تاریخی برابر با :date باشد.',
    'date_format'          => ':attribute با فرمت :format مطابقت ندارد.',
    'different'            => ':attribute و :other باید متفاوت باشند.',
    'digits'               => ':attribute باید :digits رقم باشد.',
    'digits_between'       => ':attribute باید بین :min و :max رقم باشد.',
    'dimensions'           => ':attribute ابعاد تصویر نامعتبر است.',
    'distinct'             => ':attribute دارای مقدار تکراری است.',
    'email'                => ':attribute باید یک آدرس ایمیل معتبر باشد.',
    'ends_with'            => ':attribute باید با یکی از مقادیر زیر پایان یابد: :values.',
    'exists'               => ':attribute انتخاب شده نامعتبر است.',
    'file'                 => ':attribute باید یک فایل باشد.',
    'filled'               => ':attribute باید یک مقدار داشته باشد.',
    'gt'                   => [
        'numeric' => ':attribute باید بزرگتر از :value باشد.',
        'file'    => ':attribute باید بزرگتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید بزرگتر از :value کاراکتر باشد.',
        'array'   => ':attribute باید بیشتر از :value آیتم داشته باشد.',
    ],
    'gte'                  => [
        'numeric' => ':attribute باید بزرگتر یا مساوی :value باشد.',
        'file'    => ':attribute باید بزرگتر یا مساوی :value کیلوبایت باشد.',
        'string'  => ':attribute باید بزرگتر یا مساوی :value کاراکتر باشد.',
        'array'   => ':attribute باید :value آیتم یا بیشتر داشته باشد.',
    ],
    'image'                => ':attribute باید یک تصویر باشد.',
    'in'                   => ':attribute انتخاب شده نامعتبر است.',
    'in_array'             => ':attribute وجود ندارد در :other.',
    'integer'              => ':attribute باید یک عدد صحیح باشد.',
    'ip'                   => ':attribute باید یک آدرس IP معتبر باشد.',
    'ipv4'                 => ':attribute باید یک آدرس IPv4 معتبر باشد.',
    'ipv6'                 => ':attribute باید یک آدرس IPv6 معتبر باشد.',
    'json'                 => ':attribute باید یک رشته JSON معتبر باشد.',
    'lt'                   => [
        'numeric' => ':attribute باید کمتر از :value باشد.',
        'file'    => ':attribute باید کمتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر از :value کاراکتر باشد.',
        'array'   => ':attribute باید کمتر از :value آیتم داشته باشد.',
    ],
    'lte'                  => [
        'numeric' => ':attribute باید کمتر یا مساوی :value باشد.',
        'file'    => ':attribute باید کمتر یا مساوی :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر یا مساوی :value کاراکتر باشد.',
        'array'   => ':attribute باید :value آیتم یا کمتر داشته باشد.',
    ],
    'max'                  => [
        'numeric' => ':attribute نباید بزرگتر از :max باشد.',
        'file'    => ':attribute نباید بزرگتر از :max کیلوبایت باشد.',
        'string'  => ':attribute نباید بزرگتر از :max کاراکتر باشد.',
        'array'   => ':attribute نباید بیشتر از :max آیتم داشته باشد.',
    ],
    'mimes'                => ':attribute باید یک فایل از نوع: :values باشد.',
    'mimetypes'            => ':attribute باید یک فایل از نوع: :values باشد.',
    'min'                  => [
        'numeric' => ':attribute باید حداقل :min باشد.',
        'file'    => ':attribute باید حداقل :min کیلوبایت باشد.',
        'string'  => ':attribute باید حداقل :min کاراکتر باشد.',
        'array'   => ':attribute باید حداقل :min آیتم داشته باشد.',
    ],
    'not_in'               => ':attribute انتخاب شده نامعتبر است.',
    'not_regex'            => 'فرمت :attribute نامعتبر است.',
    'numeric'              => ':attribute باید یک عدد باشد.',
    'present'              => ':attribute باید حضور داشته باشد.',
    'regex'                => 'فرمت :attribute نامعتبر است.',
    'required'             => ':attribute الزامی است.',
    'required_if'          => ':attribute الزامی است وقتی :other برابر با :value است.',
    'required_unless'      => ':attribute الزامی است مگر اینکه :other در :values باشد.',
    'required_with'        => ':attribute الزامی است وقتی :values موجود است.',
    'required_with_all'    => ':attribute الزامی است وقتی :values موجود است.',
    'required_without'     => ':attribute یا :values الزامی است.',
    'required_without_all' => ':attribute الزامی است وقتی هیچکدام از :values موجود نیست.',
    'same'                 => ':attribute و :other باید مطابقت داشته باشند.',
    'size'                 => [
        'numeric' => ':attribute باید :size باشد.',
        'file'    => ':attribute باید :size کیلوبایت باشد.',
        'string'  => ':attribute باید :size کاراکتر باشد.',
        'array'   => ':attribute باید شامل :size آیتم باشد.',
    ],
    'starts_with'          => ':attribute باید با یکی از مقادیر زیر شروع شود: :values.',
    'string'               => ':attribute باید یک رشته باشد.',
    'timezone'             => ':attribute باید یک منطقه زمانی معتبر باشد.',
    'unique'               => ':attribute قبلاً استفاده شده است.',
    'uploaded'             => 'بارگذاری :attribute ناموفق بود.',
    'url'                  => 'فرمت :attribute نامعتبر است.',
    'uuid'                 => ':attribute باید یک UUID معتبر باشد.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'grade_id' => 'کلاس',
        'subject_id' => 'موضوع',
        'topic_id' => 'عنوان',
        'name' => 'نام',
        'slug' => 'نام',
        'description' => 'توضیحات',
        'price' => 'قیمت',
        'banner' => 'بنر',
        'file' => 'فایل',
        'publish_at' => 'تاریخ انتشار',
        'email' => 'ایمیل',
        'password' => 'رمز عبور',
        'old_password' => 'رمز عبور قدیمی',
        'new_password' => 'رمز عبور جدید',
        'code' => 'کد',
        'username' => 'نام کاربری',
        'mobile' => 'موبایل',
        'avatar' => 'آواتار',
        'website' => 'وبسایت',
        'city_id' => 'شهر',
        'is_active' => 'فعال',
        'role_id' => 'نقش',
        'state' => 'وضعیت',
    ],

];
