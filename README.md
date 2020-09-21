# magento2-error-sieve

This module helps monitor errors and exceptions happening in Magento 2 instance.

It intercepts different places Magento 2 is handling and logging exceptions and collects all data into a single table.

It merges exceptions using filename and line number on witch error happened and counts each separate exceptions.

This way you can focus on exceptions by volume.

It also adds Admin Panel view in "System" -> "Tools" -> "Errors Sieve" with simple management of issues.

