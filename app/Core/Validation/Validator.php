<?php
/**
 * Validation Engine - موتور اعتبارسنجی داده‌ها
 */

namespace OmniCMS\Core\Validation;

class Validator
{
    protected $data = [];
    protected $rules = [];
    protected $errors = [];
    
    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }
    
    /**
     * Validate data
     */
    public function validate()
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Apply single rule
     */
    protected function applyRule($field, $rule)
    {
        $value = $this->data[$field] ?? null;
        
        // Parse rule with parameters
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $params = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
        
        switch ($ruleName) {
            case 'required':
                if ($value === null || $value === '') {
                    $this->addError($field, "فیلد {$field} الزامی است.");
                }
                break;
                
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "فرمت ایمیل معتبر نیست.");
                }
                break;
                
            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->addError($field, "مقدار {$field} باید عدد باشد.");
                }
                break;
                
            case 'min':
                $min = (int)$params[0];
                if ($value && strlen($value) < $min) {
                    $this->addError($field, "حداقل طول {$field} باید {$min} کاراکتر باشد.");
                }
                break;
                
            case 'max':
                $max = (int)$params[0];
                if ($value && strlen($value) > $max) {
                    $this->addError($field, "حداکثر طول {$field} باید {$max} کاراکتر باشد.");
                }
                break;
                
            case 'between':
                $min = (int)$params[0];
                $max = (int)$params[1];
                if ($value && (strlen($value) < $min || strlen($value) > $max)) {
                    $this->addError($field, "طول {$field} باید بین {$min} و {$max} کاراکتر باشد.");
                }
                break;
                
            case 'in':
                $allowed = $params;
                if ($value && !in_array($value, $allowed)) {
                    $this->addError($field, "مقدار {$field} نامعتبر است.");
                }
                break;
                
            case 'not_in':
                $disallowed = $params;
                if ($value && in_array($value, $disallowed)) {
                    $this->addError($field, "مقدار {$field} مجاز نیست.");
                }
                break;
                
            case 'url':
                if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "فرمت آدرس وب معتبر نیست.");
                }
                break;
                
            case 'alpha':
                if ($value && !ctype_alpha($value)) {
                    $this->addError($field, "{$field} فقط می‌تواند حاوی حروف باشد.");
                }
                break;
                
            case 'alpha_num':
                if ($value && !ctype_alnum($value)) {
                    $this->addError($field, "{$field} فقط می‌تواند حاوی حروف و اعداد باشد.");
                }
                break;
                
            case 'same':
                $otherField = $params[0];
                if ($value !== ($this->data[$otherField] ?? null)) {
                    $this->addError($field, "مقدار {$field} با {$otherField} مطابقت ندارد.");
                }
                break;
                
            case 'different':
                $otherField = $params[0];
                if ($value === ($this->data[$otherField] ?? null)) {
                    $this->addError($field, "مقدار {$field} نباید با {$otherField} یکسان باشد.");
                }
                break;
                
            case 'regex':
                $pattern = $params[0];
                if ($value && !preg_match($pattern, $value)) {
                    $this->addError($field, "فرمت {$field} نامعتبر است.");
                }
                break;
                
            case 'array':
                if ($value && !is_array($value)) {
                    $this->addError($field, "{$field} باید آرایه باشد.");
                }
                break;
                
            case 'integer':
                if ($value && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "{$field} باید عدد صحیح باشد.");
                }
                break;
                
            case 'boolean':
                if ($value && !in_array($value, [true, false, 0, 1, '0', '1'])) {
                    $this->addError($field, "{$field} باید مقدار بولی باشد.");
                }
                break;
                
            case 'date':
                if ($value) {
                    $date = \DateTime::createFromFormat('Y-m-d', $value);
                    if (!$date || $date->format('Y-m-d') !== $value) {
                        $this->addError($field, "فرمت تاریخ نامعتبر است. فرمت صحیح: YYYY-MM-DD");
                    }
                }
                break;
                
            case 'exists':
                // Custom validation for database existence check
                // Format: exists:table,column
                if (isset($params[0]) && isset($params[1])) {
                    $table = $params[0];
                    $column = $params[1];
                    
                    try {
                        $db = \OmniCMS\Core\Database\Connection::getInstance();
                        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
                        $stmt = $db->query($sql, ['value' => $value]);
                        $result = $stmt->fetch();
                        
                        if ($result['count'] == 0) {
                            $this->addError($field, "مقدار {$field} در پایگاه داده یافت نشد.");
                        }
                    } catch (\Exception $e) {
                        // Ignore database errors during validation
                    }
                }
                break;
                
            case 'unique':
                // Custom validation for database uniqueness check
                // Format: unique:table,column,exceptId
                if (isset($params[0]) && isset($params[1])) {
                    $table = $params[0];
                    $column = $params[1];
                    $exceptId = $params[2] ?? null;
                    
                    try {
                        $db = \OmniCMS\Core\Database\Connection::getInstance();
                        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
                        $params_db = ['value' => $value];
                        
                        if ($exceptId) {
                            $sql .= " AND id != :exceptId";
                            $params_db['exceptId'] = $exceptId;
                        }
                        
                        $stmt = $db->query($sql, $params_db);
                        $result = $stmt->fetch();
                        
                        if ($result['count'] > 0) {
                            $this->addError($field, "مقدار {$field} قبلاً ثبت شده است.");
                        }
                    } catch (\Exception $e) {
                        // Ignore database errors during validation
                    }
                }
                break;
        }
    }
    
    /**
     * Add error message
     */
    protected function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
    
    /**
     * Check if validation fails
     */
    public function fails()
    {
        return !empty($this->errors);
    }
    
    /**
     * Get all errors
     */
    public function errors()
    {
        return $this->errors;
    }
    
    /**
     * Get first error for field
     */
    public function firstError($field)
    {
        return $this->errors[$field][0] ?? null;
    }
    
    /**
     * Get all error messages as flat array
     */
    public function allErrors()
    {
        $all = [];
        foreach ($this->errors as $fieldErrors) {
            $all = array_merge($all, $fieldErrors);
        }
        return $all;
    }
}
