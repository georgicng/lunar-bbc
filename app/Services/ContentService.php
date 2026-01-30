<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class ContentService
{
    public function getMenu($type)
    {
        return Config::get("menu.{$type}");
    }

    public function getHome()
    {
        return [
            "title" => "",
            "subtitle" => "",
            "content" => $this->getMenu('home')
        ];
    }

    public function getContact()
    {
        return [
            "title" => "Contact us",
            "subtitle" => "Do you have any questions? Please do not hesitate to contact us directly",
            "content" => $this->getMenu('contact'),
            "form" => [
                "name" => ["type" => "text", "label" => "Name"],
                "subject" => ["type" => "text", "label" => "Subject"],
                "email" => ["type" => "email", "label" => "Email"],
                "message" => ["type" => "textarea", "label" => "Message"],
            ]
        ];
    }

    public function getSupport()
    {
        return [
            "title" => "Lodge a Complaint",
            "subtitle" => "Do you have any complaint about your order? Please do let us know, so we can help.",
            "content" => "",
            "form" => [
                "name" => ["type" => "text", "label" => "Name"],
                "subject" => ["type" => "text", "label" => "Subject"],
                "email" => ["type" => "email", "label" => "Email"],
                "description" => ["type" => "textarea", "label" => "Description"],
            ]
        ];
    }

    public function getFaq()
    {
        return [
            "title" => "Complaint Desk",
            "subtitle" => "Find answers to your questions below",
            "content" => [
                "MY CAKE IS BAD" => "<p>In the unlikely event that your cake is bad within 24hours of receiving it, please call us. We will retrieve the cake and replace immediately. Ensure the cake is returned intact. </p>",
                "MY CAKE COLOURS ARE NOT THE EXACT SHADE OR DESIGN" => "<p>Hand made cakes are liable to slight changes as various decorators have different styles. Pictures also create impressions that are slightly different in real life. Give access to creative license . No two cakes will ever be exactly the same.</p>",
                "MY CAKES LOOK SMALLER THAN I IMAGINED." => "<p>We will never send a cake that is smaller or bigger than paid for. To prove you have a wrong cake received with a ruler untop. Start from zero and take a picture of where it ends. All our cakes are 3inches tall.</p>",
                "MY CAKES WHERE NOT DELIVERED AT THE EXACT TIME." => "<p>Due to the unpredictable nature of Lagos traffic we do not deliver at exact timings. Please give a grace of +or-1hour.If your order is needed early please request t0 receive it a day prior to when needed.</p>",
                "MY CAKE WAS DELIVERED DAMAGED" => "<p>Please let us know the extent of damage. Cakes squashed by mishandling by our carrier will be replaced but initial cake must be sent back. If cake was mishandled after we have delivered in good condition, we will not take responsibility. Please send image of the cake and if cake is totally damaged you will get a replacement immediately. We deliver our cakes using carriage motorcycles to locations around us. And use Uber for longer distances.</p>",
                "THE MESSAGE ON MY CAKE IS WRONG." => "<p>Please be clear about your messages. Type information on separate lines if need be. Do not write answers to our questions in the same paragraph. We can however rectify, if error is from us.</p>",
                "I GOT AN ENTIRELY DIFFERENT CAKE FROM WHAT I ORDERED." => "<p>Human error/miscommunication may occur during orders. This happens in 1 out of every 1000 orders so its very rare.In the unlikely event this happens please send a mail to butterbakes@gmail.com to place an urgent complaint and we would sort it out as fast as we can.If the occasion has passed and our response came late..a full refund refund will be made.</p>",
                "MY ORDER WAS DELIVERED LATE, I DO NOT NEED THE CAKE ANYMORE." => "<p>This is an unfortunate incidence that sadly can happen for reasons beyond our control. A full refund is due in such cases. Please note also that we do not deliver at exact times. We deliver within time frames. Please give a grace of plus 1hour to these time frames due to the unpredictable nature of traffic. However we will communicate with you on your order progress once its been dispatched.</p>",
                "MY CAB DRIVER WAS NOT ATTENDED TO ON TIME AND LEFT. WHAT HAPPENS TO MY ORDER?" => "<p>This is a tricky situation. Please communicate with us when your driver will be picking orders up. We will have them boxed and ready to go. However some drivers will not be patient enough and leave before cake is handed over. We will not be responsible and we will not issue refunds in such cases.</p>",
                "I GOT TO MY PICK UP LOCATION AND MY CAKE WAS NOT THERE." => "<p>We produce all our orders fresh daily. Please give a grace of 1hour due to situations beyond our control.We will communicate with you about the progress of your order. Ensure you have adequately communicated your pick up time frame. Also note that urgent pick up orders require a 48hour notice to allow us plan properly for your pick up.</p>",
                "I WISH TO CANCEL MY ORDER." => "<p>Orders may be cancelled only if, it has not been produced. In this case, bank charges will be deducted before a refund is made. Please allow 48hours for refunds. We do not refund immediately. If however the cake has been produced we will not take refunds. Cancellations or date changes can only be made 48hours before initial collection date.</p>",
            ]
        ];
    }

    public function getTerms()
    {
        return [
            "title" => "Terms and Conditions",
            "subtitle" => "Thanks for your interest in butterbakes cakes. Before you proceed with your order we would like you to be familiar with a our processes and mode of operations.",
            "content" => [
                "affordable" =>
                "We produce quick retail cakes, and the most affordable rates we can possibly offer.",
                "flavour" =>
                "We offer 3 flavours and you may choose two flavours. dditional flavour will incur additional cost.",
                "height" =>
                "All our cakes are 3 inches high except double rate is paid for double height.",
                "extra" =>
                "All extra high cakes will be delivered through uber/taxify     only. You may however pick up from our stores or retail partners, We will not deliver them on bikes.",
                "rate" =>
                "All are cakes can be delivered door to door at a premium rate based on location and mode of delivery, or picked up from our pick up partners are a subsidized rate usually  N800.",
                "pickup" =>
                "All cakes cake be picked up from our retail partners or our stores for free.",
                "temperature" =>
                "We are buttercream specialists, all our cakes are room temperature stable unless otherwise stated.",
                "slb" =>
                "Our dessert products are SLB (see, like buy) we do not customize them with designs or decorations, only messages can changed.",
                "store" =>
                "Our dessert cakes are made with whipped cream and must be kept in the fridge.",
                "x" =>
                "All our products have a shelf life of 48hours inside or outside the fridge pls ensure you keep to this time span for  maximum freshness.",
                "care" =>
                "We do not take responsibility for products that are damaged after payment has been made and cakes have been received or  picked from our store. pls handle carefully.",
                "defect" => "We charge for redressing damaged products.",
                "return" => "Products received in good condition cannot be returned.",
                "pod" => "We do not accept payment on delivery.",
                "delivery" =>
                "We deliver within a time frame only. between 11am and 1pm / 1pm and 3pm / 3pm and 5pm.",
                "close" =>
                "We do not respond to messages after work hours and on Sundays but you may send your order via our website and we will proceed once we resume work.",
                "payment" =>
                "Full payment is required before we proceed with any transaction our fastest mode of communication is via email and phone calls.",
                "report" =>
                "Please report all unsatisfactory transaction via email at butterbakescakes@gmail.com.",
            ]
        ];
    }

    function getAbout()
    {
        return [
            "title" => "About Us",
            "subtitle" => "Welcome to the world of beautiful buttercream cakes",
            "content" => [
                "We take pride in offering our clients fluffy delicious cakes, adorned with simple but beautiful buttercream frostings and served to you at the most affordable rate we can possibly offer.",
                "WE CHOSE THE SIMPLE LIFE. Having a cake gorgeous cake should be simple too.",
                "Our team of bakers, decorators and service experts are professionally trained to give you the very best experience from cravings to delivery.",
            ]
        ];
    }
}
