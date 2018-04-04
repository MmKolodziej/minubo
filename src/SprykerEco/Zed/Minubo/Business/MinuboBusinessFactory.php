<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Minubo\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\Minubo\Business\DataExpander\OrderItemStateFlagExpander;
use SprykerEco\Zed\Minubo\Business\DataExpander\OrderItemStateFlagExpanderInterface;
use SprykerEco\Zed\Minubo\Business\Exporter\CustomerDataExporter;
use SprykerEco\Zed\Minubo\Business\Exporter\DataExporterInterface;
use SprykerEco\Zed\Minubo\Business\Exporter\Exporter;
use SprykerEco\Zed\Minubo\Business\Exporter\ExporterInterface;
use SprykerEco\Zed\Minubo\Business\Exporter\OrderDataExporter;
use SprykerEco\Zed\Minubo\Business\Writer\FileWriter;
use SprykerEco\Zed\Minubo\Business\Writer\WriterInterface;
use SprykerEco\Zed\Minubo\MinuboDependencyProvider;

/**
 * @method \SprykerEco\Zed\Minubo\MinuboConfig getConfig()
 * @method \SprykerEco\Zed\Minubo\Persistence\MinuboEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Minubo\Persistence\MinuboRepositoryInterface getRepository()
 */
class MinuboBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\Minubo\Business\Exporter\ExporterInterface
     */
    public function createDataExporter(): ExporterInterface
    {
        return new Exporter(
            $this->getEntityManager(),
            $this->getExportPlugins()
        );
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Business\Exporter\DataExporterInterface
     */
    public function createCustomerDataExporter(): DataExporterInterface
    {
        return new CustomerDataExporter(
            $this->getRepository(),
            $this->getCustomerDataFilterPlugins(),
            $this->getCustomerDataExpandPlugins(),
            $this->createFileWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Business\Exporter\DataExporterInterface
     */
    public function createOrderDataExporter(): DataExporterInterface
    {
        return new OrderDataExporter(
            $this->getRepository(),
            $this->getOrderDataFilterPlugins(),
            $this->getOrderDataExpandPlugins(),
            $this->createFileWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Business\Writer\WriterInterface
     */
    public function createFileWriter(): WriterInterface
    {
        return new FileWriter(
            $this->getUtilEncodingService(),
            $this->getFileSystemService(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Business\DataExpander\OrderItemStateFlagExpanderInterface
     */
    public function createOrderItemStateFlagExpander(): OrderItemStateFlagExpanderInterface
    {
        return new OrderItemStateFlagExpander($this->getOmsFacade());
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Plugin\MinuboExportPluginInterface[]
     */
    protected function getExportPlugins()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::MINUBO_EXPORT_PLUGINS_STACK);
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Plugin\MinuboDataFilterInterface[]
     */
    protected function getCustomerDataFilterPlugins()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::MINUBO_CUSTOMER_DATA_FILTER_PLUGINS_STACK);
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Plugin\MinuboDataFilterInterface[]
     */
    protected function getOrderDataFilterPlugins()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::MINUBO_ORDER_DATA_FILTER_PLUGINS_STACK);
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Plugin\MinuboDataExpanderInterface[]
     */
    protected function getCustomerDataExpandPlugins()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::MINUBO_CUSTOMER_DATA_EXPANDER_PLUGINS_STACK);
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Plugin\MinuboDataExpanderInterface[]
     */
    protected function getOrderDataExpandPlugins()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::MINUBO_ORDER_DATA_EXPANDER_PLUGINS_STACK);
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Service\MinuboToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Service\MinuboToFileSystemServiceInterface
     */
    protected function getFileSystemService()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::SERVICE_FILE_SYSTEM);
    }

    /**
     * @return \SprykerEco\Zed\Minubo\Dependency\Facade\MinuboToOmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(MinuboDependencyProvider::FACADE_OMS);
    }
}