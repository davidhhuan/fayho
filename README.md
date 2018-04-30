# fayho
fayho framework depend on swoft



# 关于Services命名空间解说
- 所有类，都是针对*RPC 提供者*和*RPC 调用者*的
- 子目录，按不同子系统进行划分。如现有的Store目录，它是Store这个子系统的。所有的接口都是由Store子系统提供
- 每个子目录下面，都必须包含README.md，里面至少提供配置文件的内容