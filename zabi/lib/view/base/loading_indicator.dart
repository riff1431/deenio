import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class LoadingIndicator extends StatelessWidget {
  const LoadingIndicator({super.key});

  @override
  Widget build(BuildContext context) {
    // Loading cheacker--->
    final widget = (GetPlatform.isIOS)
        ? const CupertinoActivityIndicator()
        : const CircularProgressIndicator();
    return Container(
      alignment: Alignment.center,
      child: widget,
    );
  }
}
