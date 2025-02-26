<!--Heading-->
![npm](https://img.shields.io/npm/v/npm
) ![php](https://img.shields.io/badge/php-v8.2.0-v8
) ![laravel](https://img.shields.io/badge/laravel-v12.0-v8
)

---

# _Installation_

```diff
+PHP version 8.2 is required
```



# _Configuration_
```diff
AWS credentials 

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

# _Setup_
```
>>Ensure you have AWS KEY and SECRET KEY on your env. Set the default region to us-east-1
>>You may require to have AWS CLI and credentials installed. If you are on linux, follow the commands below and fill the prompts 


&*aws configure
&*aws sts get-caller-identity

>>You should see an output similar to this: 

{
    "UserId": "xxxxx",
    "Account": "xxxx",
    "Arn": "arn:aws:iam::xxxxx:root"
}

>>You might not be using root account, that does not matter.


After that now run below command to push env to AWS Parameter Store

&*php artisan ssm:push-env

>>The env will be stored on /my-app/env
