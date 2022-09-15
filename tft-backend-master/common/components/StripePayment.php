<?php
namespace common\components;
use Yii;
class StripePayment extends \yii\base\Component {
    public function webhook(){
        $input = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = file_get_contents('php://input');
            $body = json_decode($input);	
        }
        $event = null;
        $user = \common\models\UserAdditionalInfo::find()->where(['user_id'=>82])->one();
        $user->address = $user->address+1;
        $user->save(false);
        return[
            'status'=>true
        ];
        try {
            // Make sure the event is coming from Stripe by checking the signature header
            $event = \Stripe\Webhook::constructEvent($input, $_SERVER['HTTP_STRIPE_SIGNATURE'],'whsec_Tu5Wq55Oa1IIjThpJCQYTS7dnSjQYuvB');
        }
        catch (Exception $e) {
            http_response_code(403);
            echo json_encode([ 'error' => $e->getMessage() ]);
            exit;
        }

        $details = '';

        $type = $event['type'];
        $object = $event['data']['object'];
        
        // Handle the event
        // Review important events for Billing webhooks
        // https://stripe.com/docs/billing/webhooks
        // Remove comment to see the various objects sent for this sample
        switch ($type) {
            case 'invoice.paid':
                // The status of the invoice will show up as paid. Store the status in your
                // database to reference when a user accesses your service to avoid hitting rate
                // limits.
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            case 'invoice.payment_failed':
                // If the payment fails or the customer does not have a valid payment method,
                // an invoice.payment_failed event is sent, the subscription becomes past_due.
                // Use this webhook to notify your user that their payment has
                // failed and to retrieve new card details.
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            case 'invoice.finalized':
                // If you want to manually send out invoices to your customers
                // or store them locally to reference to avoid hitting Stripe rate limits.
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            case 'customer.subscription.deleted':
                // handle subscription cancelled automatically based
                // upon your subscription settings. Or if the user
                // cancels it.
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            case 'customer.subscription.trial_will_end':
                // Send notification to your user that the trial will end
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            // ... handle other event types
            default:
            // Unhandled event type
        }
       return[
           'status'=>true
       ];
        
    }
    public function chargePayment($price,$token,$msg=""){
        $stripe = new \Stripe\StripeClient(
            Yii::$app->setting->val('stripe_secrete_key')
        );
        try{
            return $stripe->charges->create([
                'amount' => $price*100,
                'currency' => 'USD',
                'source' => $token,
                'description' => $msg
            ]);
        }catch(\Stripe\Exception\CardException $e) {
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new \yii\web\HttpException(404, $e->getError()->message);
        }
    }
    public function updateCustomerCard($customer_id,$token){
        $stripe = new \Stripe\StripeClient(
            \Yii::$app->setting->val('stripe_secrete_key')
        );
        try {  
            $customer = $stripe->customers->update(
                $customer_id,
                [
                'source' => $token,
                ]
            );
            return $customer->id;
        } catch(\Stripe\Exception\CardException $e) {
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new \yii\web\HttpException(404, $e->getError()->message);
        }
    }
    public function createCard($customer_id,$token){
        $stripe = new \Stripe\StripeClient(
            \Yii::$app->setting->val('stripe_secrete_key')
        );
        try { 
          $card = $stripe->customers->createSource(
            $customer_id,
            ['source' => $token]
          );
          return $card->id;

        }catch(\Stripe\Exception\CardException $e) {
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new \yii\web\HttpException(404, $e->getError()->message);
        }
    }
    public function createCustomer($name,$email){
        $stripe = new \Stripe\StripeClient(
            \Yii::$app->setting->val('stripe_secrete_key')
        );
        try {  
            $customer = $stripe->customers->create([
                'name'=>$name,
                'email' => $email
            ]);
            return $customer->id;
        } catch(\Stripe\Exception\CardException $e) {
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new \yii\web\HttpException(404, $e->getError()->message);
        }
    }
    public function createSubscription($customer_id,$plan_id,$metadata=[]){
        $stripe = new \Stripe\StripeClient(
            \Yii::$app->setting->val('stripe_secrete_key')
        );
        try { 
            $subscription = $stripe->subscriptions->create([
                "customer" => $customer_id,
                "items" => [
                    [
                        "plan" => $plan_id,
                    ], 
                ], 
                "metadata"=>$metadata
            ]); 
            return $subscription;
        } catch(\Stripe\Exception\CardException $e) {
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            print_r($e);die;
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new \yii\web\HttpException(404, $e->getError()->message);
        }
    }
    public function cancelSubscription($subscription_id){
        $stripe = new \Stripe\StripeClient(
            Yii::$app->setting->val('stripe_secrete_key')
        );
        try{
            return $stripe->subscriptions->cancel(
                $subscription_id,
                []
            );
        }catch(\Stripe\Exception\CardException $e) {
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new \yii\web\HttpException(404, $e->getError()->message);
        }
    }
    public function retrieveSubscription($subscription_id){
        $stripe = new \Stripe\StripeClient(
            Yii::$app->setting->val('stripe_secrete_key')
        );
        try{
            return $stripe->subscriptions->retrieve(
                $subscription_id,
                []
            );
        }catch(\Stripe\Exception\CardException $e) {
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new \yii\web\HttpException(404, $e->getError()->message);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new \yii\web\HttpException(404, $e->getError()->message);
        }
    }
}
?>