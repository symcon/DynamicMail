<?php

declare(strict_types=1);
class DynamicMail extends IPSModule
{
    public function Create(): void
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyInteger('SMTPInstance', 0);
        $this->RegisterPropertyString('DynamicSubject', '');
        $this->RegisterPropertyString('DynamicText', '');
    }

    public function Destroy(): void
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function ApplyChanges(): void
    {
        //Never delete this line!
        parent::ApplyChanges();

        if (IPS_GetKernelRunlevel() == KR_READY) {
            $this->checkSMTPInstance();
        }

        //Unregister all reference
        foreach ($this->GetReferenceList() as $reference) {
            $this->UnregisterReference($reference);
        }

        //Register all references
        $text = $this->ReadPropertyString('DynamicSubject') . ' ' . $this->ReadPropertyString('DynamicText');
        $placeholders = $this->getPlaceholder($text);
        foreach ($placeholders as $key => $placeholder) {
            $this->RegisterReference($placeholder);
        }
    }

    public function GetConfigurationForm(): string
    {
        //check if the Variables are valid
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);
        $form['actions'][1]['caption'] = $this->Translate('Preview E-Mail with Subject') . ' :' . $this->replacePlaceholder($dynamicText = $this->ReadPropertyString('DynamicSubject'));
        $form['actions'][2]['caption'] = $this->replacePlaceholder($dynamicText = $this->ReadPropertyString('DynamicText'));
        $form['actions'][3]['values'] = $this->analyzeVariable();
        return json_encode($form);
    }

    public function SendMail(): bool
    {
        //validate that the smtp instance is ok
        if (!$this->checkSMTPInstance()) {
            return false;
        }

        $dynamicText = $this->replacePlaceholder($this->ReadPropertyString('DynamicText'));
        $dynamicSubject = $this->replacePlaceholder($this->ReadPropertyString('DynamicSubject'));
        return SMTP_SendMail(
            $this->ReadPropertyInteger('SMTPInstance'),
            $dynamicSubject,
            $dynamicText
        );
    }

    private function analyzeVariable(): array
    {
        $values = [];
        $getPlaceholder = function (string $text) use (&$values): void
        {
            $placeholders = $this->getPlaceholder($text);
            foreach ($placeholders as $key => $placeholder) {
                $status = IPS_VariableExists(intval($placeholder)) ? 'OK' : $this->Translate('Invalid');
                $values[] = [
                    'VariableID'     => intval($placeholder),
                    'CurrentValue'   => $status == 'OK' ? GetValueFormatted($placeholder) : '',
                    'Status'         => $status,
                    'rowColor'       => $status != 'OK' ? '#FFC0C0' : ''
                ];
            }
        };
        $getPlaceholder($this->ReadPropertyString('DynamicSubject'));
        $getPlaceholder($this->ReadPropertyString('DynamicText'));

        return $values;
    }

    private function checkSMTPInstance(): bool
    {
        $instance = $this->ReadPropertyInteger('SMTPInstance');
        if (!IPS_InstanceExists($instance)) {
            $this->SetStatus(201);
            return false;
        }
        $instanceInfo = IPS_GetInstance($instance);
        if ($instanceInfo['InstanceStatus'] >= 200 || $instanceInfo['InstanceStatus'] >= 104) {
            $this->SetStatus(202);
            return false;
        }
        $this->SetStatus(102);
        return true;
    }

    private function getPlaceholder(string $text): array
    {
        preg_match_all("/\{([1-9]{1}[0-9]{4})\}/", $text, $placeholders);
        return $placeholders[1];
    }

    private function replacePlaceholder(string $dynamicText): string
    {
        //replace the {12345} with the variable value
        $replace = function ($matches): string
        {
            return IPS_VariableExists($matches[1]) ? GetValueFormatted($matches[1]) : $matches[0];
        };
        $dynamicText = preg_replace_callback("/\{([1-9]{1}[0-9]{4})\}/", $replace, $dynamicText);
        return $dynamicText;
    }
}
