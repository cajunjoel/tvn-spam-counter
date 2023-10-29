== RULES.JSON FILE

Organized based on the name corresponding to each round of the event.

**url** is the path to the Forum Thread

**name** is a descriptive name used for the cache file

**rules** are the validation rules for each post. An array of named objects. Each object has the following possible elements

* pattern
* backreference
* count
* message

If `pattern` and `backrefence` are both supplied, `preg_match()` is called. The $matches is checked to see if there is a value in which case the rules passes.

If the name of the rule is `min_word_count` then the `count` value is used as the lower threshold.

`message` is used for reporting purposes when the rule failed.

During processing, rule checking stops when the first failure is found.


