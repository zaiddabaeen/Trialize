<img src="https://github.com/zaiddabaeen/trialize/blob/master/public/images/shorten_small.png?raw=true"/>
# Trialize
## Zend Framework 2 Project and Android library
Manage your trial Android apps.

Using Trialize you will be able to set up a trial period for your development applications in real-time.

Add this project to your server and manage your trial android apps.

###Server Installation
* Copy the project to your server DocumentRoot, and create a vhost.<br />
* Import the table data/shorten.sql, you might need to change the database name to suit your databases.<br />
* Edit IndexController in loginAction to change the login credentials.

###Android Installation
* Add to your dependencies in `build.gradle` for jCenter:
```gradle
    compile com.zaiddabaeen.trialize:sdk:1.+
```

* Set up the event `isValid` to check the validity of the trial period:
```java
    Trialize.isValid(this, new Trialize.TrialListener() {
        @Override
        public void onGoing(int days_left) {}

        @Override
        public void ended() {}

        @Override
        public void error() {}
    }, true);
```

> onGoing denotes that the trial is still valid and provides the number of days remaining
> ended denotes that the trial has ended
> error denotes that the request cannot be made due to lack of internet connectivity, server failures, or inexistence of a record for that app.