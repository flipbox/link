<?php

namespace flipbox\link\controllers;

use Craft;
use craft\helpers\ArrayHelper;
use craft\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use flipbox\link\Link;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TypeController extends Controller
{

    /**
     * @return Response
     * @throws HttpException
     */
    public function actionSettings(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $view = $this->getView();

        $type = Link::getInstance()->getType()->find(
            Craft::$app->getRequest()->getRequiredBodyParam('type')
        );
        
        if (!$type) {
            throw new HttpException("Type not found");
        }

        $html = $view->renderTemplate(
            'link/_components/fieldtypes/Link/type',
            [
                'type' => $type,
                'namespace' => Craft::$app->getRequest()->getRequiredBodyParam('namespace')
            ]
        );

        return $this->asJson([
            'label' => $type::displayName(),
            'paneHtml' => $html,
            'headHtml' => $view->getHeadHtml(),
            'footHtml' => $view->getBodyHtml(),
        ]);
    }
}
