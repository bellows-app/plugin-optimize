<?php

use Bellows\Plugins\Optimize;

it('can update the deploy script', function () {
    $result = $this->plugin(Optimize::class)->deploy();

    collect([
        'config:cache',
        'route:cache',
        'view:cache',
        'event:cache',
    ])->each(
        fn ($toInsert) => expect($result->getUpdateDeployScript())->toContain($toInsert)
    );
});
